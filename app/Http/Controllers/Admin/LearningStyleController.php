<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LearningStyleResultsExport;
use App\Http\Controllers\Controller;
use App\Models\LearningStyleQuestion;
use App\Models\LearningStyleResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LearningStyleController extends Controller
{
    public function index()
    {
        return view('admin.learning-style.index');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'completed_at' => 'required|date',
        ]);

        $answers = $validated['answers'];

        // Hitung skor per dimensi (1–4)
        $dimensionScores = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

        foreach ($answers as $i => $ans) {
            $dimension = ($i % 4) + 1;
            $dimensionScores[$dimension] += $ans['point'];
        }

        // Mapping Felder-Soloman dimensions
        $dimensionMap = [
            1 => ['name' => 'Active–Reflective', 'positive' => 'Active', 'negative' => 'Reflective'],
            2 => ['name' => 'Sensing–Intuitive', 'positive' => 'Sensing', 'negative' => 'Intuitive'],
            3 => ['name' => 'Visual–Verbal', 'positive' => 'Visual', 'negative' => 'Verbal'],
            4 => ['name' => 'Sequential–Global', 'positive' => 'Sequential', 'negative' => 'Global'],
        ];

        // Tentukan intensitas
        $getIntensity = function ($absScore) {
            if ($absScore >= 9) return 'Strong';
            if ($absScore >= 5) return 'Moderate';
            if ($absScore >= 1) return 'Mild';
            return 'Balanced';
        };

        // Susun detail per dimensi
        $dimensionResults = [];

        foreach ($dimensionScores as $dim => $score) {
            $absScore = abs($score);
            $direction = $score > 0
                ? $dimensionMap[$dim]['positive']
                : ($score < 0 ? $dimensionMap[$dim]['negative'] : 'Balanced');

            $dimensionResults[] = [
                'dimension' => $dimensionMap[$dim]['name'],
                'score' => $score,
                'direction' => $direction,
                'intensity' => $getIntensity($absScore),
            ];
        }

        $summaryResult = collect($dimensionResults)
            ->map(fn($d) => "{$d['intensity']} {$d['direction']}")
            ->implode(', ');

        // Simpan ke database
        $result = LearningStyleResult::create([
            'user_id' => Auth::id(),
            'result' => $summaryResult,
            'details' => $dimensionResults,
            'completed_at' => $validated['completed_at'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Learning style quiz submitted successfully.',
            'result' => $result,
        ]);
    }

    public function destroy($id)
    {
        $learning_style = LearningStyleQuestion::find($id);
        $learning_style->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil dihapus'
        ]);
    }

    public function all()
    {
        return response()->json(LearningStyleQuestion::orderBy('id')->get());
    }

    public function save(Request $request)
    {
        // Ambil semua input tanpa auto redirect
        $data = $request->all();

        // Buat array dasar untuk disimpan
        $saveData = [
            'question' => $data['question'] ?? '',
            'answers'  => $data['answers'] ?? [],
            'is_active' => $data['is_active'] ?? true
        ];

        // Jika answers kosong atau tidak valid, isi placeholder default (2 jawaban kosong)
        if (empty($saveData['answers']) || !is_array($saveData['answers'])) {
            $saveData['answers'] = [
                ['text' => '', 'point' => 0],
                ['text' => '', 'point' => 0],
            ];
        }

        // Jalankan save, walau sebagian kosong
        $model = LearningStyleQuestion::updateOrCreate(
            ['id' => $data['id'] ?? null],
            $saveData
        );

        // Kirim response JSON biar AJAX bisa update status
        return response()->json([
            'success' => true,
            'id' => $model->id,
            'message' => 'Saved successfully',
            'is_partial' => (
                empty($data['question']) ||
                empty($data['answers'][0]['text'] ?? '') ||
                empty($data['answers'][1]['text'] ?? '')
            ),
        ]);
    }

    public function history()
    {
        $participants = LearningStyleResult::with('user')->get()->map(function ($item) {
            $details = $item->details;

            // Map ke format "Intensity Direction"
            $mapped = [
                'Pemrosesan' => isset($details[0])
                    ? ucfirst(strtolower($details[0]['intensity'] ?? '-')) . ' ' . ucfirst(strtolower($details[0]['direction'] ?? '-'))
                    : '-',
                'Persepsi' => isset($details[1])
                    ? ucfirst(strtolower($details[1]['intensity'] ?? '-')) . ' ' . ucfirst(strtolower($details[1]['direction'] ?? '-'))
                    : '-',
                'Input' => isset($details[2])
                    ? ucfirst(strtolower($details[2]['intensity'] ?? '-')) . ' ' . ucfirst(strtolower($details[2]['direction'] ?? '-'))
                    : '-',
                'Pemahaman' => isset($details[3])
                    ? ucfirst(strtolower($details[3]['intensity'] ?? '-')) . ' ' . ucfirst(strtolower($details[3]['direction'] ?? '-'))
                    : '-',
            ];

            return [
                'id' => $item->id,
                'user' => $item->user,
                'result' => $item->result,
                'mapped' => $mapped,
                'created_at' => $item->created_at->format('d M Y'),
            ];
        });

        return view('admin.learning-style.history', compact('participants'));
    }

    public function export()
    {
        $filename = 'learning_style_results_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new LearningStyleResultsExport, $filename);
    }
}
