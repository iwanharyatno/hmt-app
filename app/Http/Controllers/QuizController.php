<?php

namespace App\Http\Controllers;

use App\Models\HmtHistory;
use App\Models\HmtQuestion;
use App\Models\LearningStyleQuestion;
use App\Models\LearningStyleResult;
use App\Models\Setting;
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
        ];

        $questions = HmtQuestion::where('is_active', true)->get()->map(function ($q) {
            return [
                'id'            => $q->id,
                'question_path' => Storage::url($q->question_path),
                'answer_paths'  => collect($q->answer_paths ?? [])
                    ->map(fn($p) => $p ? Storage::url($p) : null)
                    ->toArray(),
                'correct_index' => $q->correct_index,
            ];
        });

        $examples = HmtQuestion::where('is_example', true)->get()->map(function ($q) {
            return [
                'id'            => $q->id,
                'question_path' => Storage::url($q->question_path),
                'answer_paths'  => collect($q->answer_paths ?? [])
                    ->map(fn($p) => $p ? Storage::url($p) : null)
                    ->toArray(),
                'correct_index' => $q->correct_index,
                'solution_description' => $q->solution_description
            ];
        });

        return view('user.quiz.hmt', compact('questions', 'settings', 'examples'));
    }

    /**
     * Simpan jawaban user (AJAX)
     */
    public function saveHmtAnswer(Request $request)
    {
        $validated = $request->validate([
            'question_id'  => 'required|exists:hmt_questions,id',
            'answer_index' => 'required|integer|min:0',
        ]);

        $userId = Auth::user()->id;

        // Hitung attempt keberapa
        $attempts = HmtHistory::where('question_id', $validated['question_id'])
            ->where('user_id', $userId)
            ->count() + 1;

        $history = HmtHistory::create([
            'question_id' => $validated['question_id'],
            'user_id'     => $userId,
            'answer_index' => $validated['answer_index'],
            'answered_at' => now(),
            'attempts'    => $attempts,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jawaban tersimpan',
            'data'    => $history,
        ]);
    }
}
