<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HmtQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HmtController extends Controller
{
    /**
     * Display a listing of HMT questions.
     */
    public function index()
    {
        $questions = HmtQuestion::orderBy('created_at', 'desc')->get();
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

        // Update correct index
        $question->correct_index = $request->correct_index;

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
}
