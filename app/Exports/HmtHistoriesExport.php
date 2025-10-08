<?php

namespace App\Exports;

use App\Models\HmtHistory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HmtHistoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return HmtHistory::with(['question', 'user'])
            ->get()
            ->map(function ($history) {
                $answeredAt = $history->answered_at;
                return [
                    'ID'          => $history->id,
                    'User ID'     => $history->user_id,
                    'User'        => $history->user?->name ?? 'Guest',
                    'Question ID'    => $history->question?->id,
                    'Answer'      => $history->answer_index,
                    'Correct'     => $history->answer_index == $history->question?->correct_index,
                    'Attempts'    => $history->attempts,
                    'Timestamp'   => $answeredAt ? $answeredAt->getTimestampMs() : null,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'User ID',
            'User',
            'Question ID',
            'Answer',
            'Correct',
            'Attempts',
            'Timestamp',
        ];
    }
}
