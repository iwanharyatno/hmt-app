<?php

namespace App\Http\Controllers;

use App\Models\HmtHistory;
use App\Models\HmtQuestion;
use App\Models\HmtSession;
use App\Models\LearningStyleQuestion;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    public function learningStyle()
    {
        $user = Auth::user()->load(['learningStyleResults' => fn($q) => $q->latest()->limit(1)]);
        $latestLearningStyle = $user->learningStyleResults->first();

        if ($latestLearningStyle) {
            return redirect()->route('user.dashboard')->with('error', 'Anda sudah pernah mengisi kuisioner ini.');
        }

        $questions = LearningStyleQuestion::where('is_active', true)
            ->orderBy('id')
            ->get(['id', 'question', 'answers']);

        $settings = [
            Setting::LS_DESCRIPTION => Setting::getValue(Setting::LS_DESCRIPTION, ''),
            Setting::LS_PRIVACY => Setting::getValue(Setting::LS_PRIVACY, ''),
        ];

        return view('user.quiz.learning-style', compact('questions', 'settings'));
    }

    /**
     * Halaman awal kuis HMT
     */
    public function hmt()
    {
        $user = Auth::user();

        $user = Auth::user()->load(['learningStyleResults' => fn($q) => $q->latest()->limit(1)]);
        $latestLearningStyle = $user->learningStyleResults->first();

        $allowLs = Setting::getValue(Setting::WEB_ALLOW_LS);

        if (!$latestLearningStyle && !$allowLs) {
            return redirect()->route('user.dashboard')->with('error', 'Silakan isi kuisioner Learning Style terlebih dahulu sebelum memulai HMT.');
        }

        $settings = [
            Setting::HMT_DURATION => Setting::getValue(Setting::HMT_DURATION, ''),
            Setting::HMT_DESCRIPTION => Setting::getValue(Setting::HMT_DESCRIPTION, ''),
            Setting::HMT_PRIVACY => Setting::getValue(Setting::HMT_PRIVACY, ''),
            Setting::HMT_SOAL_FIRST => boolval(Setting::getValue(Setting::HMT_SOAL_FIRST, false)),
            Setting::HMT_SHOW_TIME_LEFT => boolval(Setting::getValue(Setting::HMT_SHOW_TIME_LEFT, false)),
            Setting::HMT_SHOW_QUESTION_PROGRESS => boolval(Setting::getValue(Setting::HMT_SHOW_QUESTION_PROGRESS, false)),
        ];

        $questions = HmtQuestion::where('is_active', true)->get()->map(function ($q) {
            return [
                'id' => $q->id,
                'question_path' => Storage::url($q->question_path),
                'answer_paths' => collect($q->answer_paths ?? [])
                    ->map(fn($p) => $p ? Storage::url($p) : null)
                    ->toArray(),
                'correct_index' => $q->correct_index,
            ];
        });

        $examples = HmtQuestion::where('is_example', true)->get()->map(function ($q) {
            return [
                'id' => $q->id,
                'question_path' => Storage::url($q->question_path),
                'answer_paths' => collect($q->answer_paths ?? [])
                    ->map(fn($p) => $p ? Storage::url($p) : null)
                    ->toArray(),
                'correct_index' => $q->correct_index,
                'solution_description' => $q->solution_description
            ];
        });

        return view('user.quiz.hmt', compact('questions', 'settings', 'examples'));
    }
    /**
     * Start new HMT session
     */
    public function startSession(Request $request)
    {
        $validated = $request->validate([
            'started_at' => 'required|date'
        ]);

        $user = Auth::user();

        // Hitung attempt keberapa user
        $latestAttempt = HmtSession::where('user_id', $user->id)->max('attempts') ?? 0;
        $startedAt = Carbon::parse($validated['started_at']);

        $session = HmtSession::create([
            'user_id' => $user->id,
            'attempts' => $latestAttempt + 1,
            'started_at' => $startedAt,
        ]);

        return response()->json([
            'message' => 'Session started successfully',
            'session_id' => $session->id,
            'attempt' => $session->attempts,
            'started_at' => $session->started_at,
        ]);
    }

    /**
     * Submit answer to current session
     */
    public function submitAnswer(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:hmt_sessions,id',
            'question_id' => 'required|exists:hmt_questions,id',
            'answer_index' => 'nullable|integer',
            'answered_at' => 'nullable|date'
        ]);

        $session = HmtSession::findOrFail($validated['session_id']);

        // Optional: verify ownership of session
        if ($session->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to submit for this session.');
        }

        $answeredAt = $validated['answered_at'] ? Carbon::parse($validated['answered_at']) : null;

        $history = HmtHistory::create([
            'session_id' => $session->id,
            'question_id' => $validated['question_id'],
            'answer_index' => $validated['answer_index'],
            'answered_at' => $answeredAt,
        ]);

        $isCorrect = isset($validated['answer_index']) && $validated['answer_index'] && HmtQuestion::find($validated['question_id'])->correct_index == $validated['answer_index'];

        return response()->json([
            'message' => 'Answer recorded successfully',
            'is_correct' => $isCorrect,
            'answered_at' => $history->answered_at,
        ]);
    }

    /**
     * Finish current session
     */
    public function finishSession(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:hmt_sessions,id',
            'finished_at' => 'required|date'
        ]);

        $session = HmtSession::findOrFail($validated['session_id']);
        $finished_at = Carbon::parse($validated['finished_at']);

        if ($session->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to finish this session.');
        }

        $session->update([
            'finished_at' => $finished_at,
        ]);

        return response()->json([
            'message' => 'Session finished successfully',
            'finished_at' => $session->finished_at,
        ]);
    }
}
