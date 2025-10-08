<?php

namespace App\Exports;

use App\Models\LearningStyleResult;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LearningStyleResultsExport implements FromView
{
    public function view(): View
    {
        $participants = LearningStyleResult::with('user')->get()->map(function ($item) {
            $details = $item->details;

            $mapped = [];
            foreach ([0, 1, 2, 3] as $i) {
                $mapped[] = isset($details[$i])
                    ? ucfirst(strtolower($details[$i]['intensity'] ?? '-')) . ' ' . ucfirst(strtolower($details[$i]['direction'] ?? '-'))
                    : '-';
            }

            return [
                'id' => $item->user->id,
                'user' => $item->user->name,
                'pemrosesan' => $mapped[0],
                'persepsi' => $mapped[1],
                'input' => $mapped[2],
                'pemahaman' => $mapped[3],
                'tanggal' => $item->created_at->format('d M Y'),
            ];
        });

        return view('admin.learning-style.exports', compact('participants'));
    }
}
