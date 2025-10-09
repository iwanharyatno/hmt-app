<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HmtQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Exports\HmtHistoriesExport;
use App\Exports\HmtHistorySingleExport;
use App\Models\HmtHistory;
use App\Models\HmtSession;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class HmtController extends Controller
{
    /**
     * Display a listing of HMT questions.
     */
    public function index()
    {
        $questions = HmtQuestion::orderBy('created_at', 'asc')->get();
        return view('admin.hmt.index', compact('questions'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create()
    {
        return view('admin.hmt.create');
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_path'  => 'required|file|mimes:jpg,jpeg,png,gif',
            'answer_paths.*' => 'nullable|file|mimes:jpg,jpeg,png,gif',
            'correct_index'  => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Upload gambar soal
        $questionPath = $request->file('question_path')->store('hmt/questions', 'public');

        // Upload semua jawaban
        $answerPaths = [];
        if ($request->hasFile('answer_paths')) {
            foreach ($request->file('answer_paths') as $file) {
                if ($file) {
                    $answerPaths[] = $file->store('hmt/answers', 'public');
                } else {
                    $answerPaths[] = null;
                }
            }
        }

        // Simpan ke DB
        $question = HmtQuestion::create([
            'question_path' => $questionPath,
            'answer_paths'  => $answerPaths,
            'correct_index' => $request->correct_index,
            'is_active' => $request->has('is_active')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil disimpan',
            'data'    => $question,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $question = HmtQuestion::findOrFail($id);

        return view('admin.hmt.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $question = HmtQuestion::findOrFail($id);

        $request->validate([
            'question_path'  => 'nullable|file|mimes:jpg,jpeg,png,gif',
            'answer_paths.*' => 'nullable|file|mimes:jpg,jpeg,png,gif',
            'correct_index'  => 'required|integer|min:0',
        ]);

        // Jika ada gambar soal baru
        if ($request->hasFile('question_path')) {
            // Hapus file lama
            if ($question->question_path && Storage::disk('public')->exists($question->question_path)) {
                Storage::disk('public')->delete($question->question_path);
            }

            // Simpan file baru
            $questionPath = $request->file('question_path')->store('hmt/questions', 'public');
            $question->question_path = $questionPath;
        }

        // Update gambar jawaban
        $answerPaths = $question->answer_paths ?? [];
        if ($request->hasFile('answer_paths')) {
            foreach ($request->file('answer_paths') as $index => $file) {
                if ($file) {
                    // Jika ada file lama di index ini, hapus
                    if (isset($answerPaths[$index]) && Storage::disk('public')->exists($answerPaths[$index])) {
                        Storage::disk('public')->delete($answerPaths[$index]);
                    }

                    // Upload baru
                    $answerPaths[$index] = $file->store('hmt/answers', 'public');
                }
            }
            $question->answer_paths = $answerPaths;
        }

        $question->correct_index = $request->correct_index;
        $question->is_active = $request->has('is_active');

        $question->save();

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil diperbarui',
            'data'    => $question,
        ]);
    }

    public function destroy($id)
    {
        $question = HmtQuestion::findOrFail($id);

        // Hapus file soal
        if ($question->question_path && Storage::disk('public')->exists($question->question_path)) {
            Storage::disk('public')->delete($question->question_path);
        }

        // Hapus file jawaban
        if ($question->answer_paths && is_array($question->answer_paths)) {
            foreach ($question->answer_paths as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil dihapus',
        ]);
    }

    public function history()
    {
        $sessions = \App\Models\HmtSession::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.hmt.history', compact('sessions'));
    }

    public function showAnswer($id)
    {
        $session = \App\Models\HmtSession::with(['user', 'histories.question'])->findOrFail($id);

        return view('admin.hmt.show', compact('session'));
    }

    public function exportHistoriesCsv()
    {
        // Ekspor hanya sesi terakhir dari setiap user
        $filename = 'hmt_histories_latest_sessions_' . now()->format('Ymd_His') . '.csv';
        return Excel::download(new HmtHistoriesExport, $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportHistoriesSingleCsv($sessionId)
    {
        $session = HmtSession::with('user')->findOrFail($sessionId);

        $filename = sprintf(
            'hmt_history_%s_session_%s_%s.csv',
            str_replace(' ', '_', strtolower($session->user->name ?? 'unknown')),
            $session->attempts,
            now()->format('Ymd_His')
        );

        return Excel::download(new HmtHistorySingleExport($sessionId), $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Start new HMT session
     */
    public function startSession(Request $request)
    {
        $user = Auth::user();

        // Hitung attempt keberapa user
        $latestAttempt = HmtSession::where('user_id', $user->id)->max('attempts') ?? 0;

        $session = HmtSession::create([
            'user_id' => $user->id,
            'attempts' => $latestAttempt + 1,
            'started_at' => now(),
        ]);

        return response()->json([
            'message' => 'Session started successfully',
            'session_id' => $session->id,
            'attempt' => $session->attempts,
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
            'answer_index' => 'required|integer|min:0',
        ]);

        $session = HmtSession::findOrFail($validated['session_id']);

        // Optional: verify ownership of session
        if ($session->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to submit for this session.');
        }

        HmtHistory::create([
            'session_id' => $session->id,
            'question_id' => $validated['question_id'],
            'answer_index' => $validated['answer_index'],
            'answered_at' => now(),
        ]);

        return response()->json([
            'message' => 'Answer recorded successfully',
        ]);
    }

    /**
     * Finish current session
     */
    public function finishSession(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:hmt_sessions,id',
        ]);

        $session = HmtSession::findOrFail($validated['session_id']);

        if ($session->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to finish this session.');
        }

        $session->update([
            'finished_at' => now(),
        ]);

        return response()->json([
            'message' => 'Session finished successfully',
            'finished_at' => $session->finished_at,
        ]);
    }
}
