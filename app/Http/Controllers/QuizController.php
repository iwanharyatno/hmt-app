<?php

namespace App\Http\Controllers;

use App\Models\HmtHistory;
use App\Models\HmtQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    /**
     * Halaman awal kuis HMT
     */

    public function hmt()
    {
        $questions = HmtQuestion::all()->map(function ($q) {
            return [
                'id'            => $q->id,
                'question_path' => Storage::url($q->question_path),
                'answer_paths'  => collect($q->answer_paths ?? [])
                    ->map(fn($p) => $p ? Storage::url($p) : null)
                    ->toArray(),
                'correct_index' => $q->correct_index,
            ];
        });

        return view('user.quiz.hmt', compact('questions'));
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
