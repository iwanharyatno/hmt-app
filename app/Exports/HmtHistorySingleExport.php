<?php

namespace App\Exports;

use App\Models\HmtHistory;
use Maatwebsite\Excel\Concerns\FromCollection;

class HmtHistorySingleExport implements FromCollection
{
    private $userId;

    function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return HmtHistory::with(['question', 'user'])
            ->where('user_id', $this->userId)
            ->get()
            ->map(function ($history) {
                $answeredAt = $history->answered_at;
                return [
                    'ID'          => $history->id,
                    'User ID'     => $history->user_id,
                    'User'        => $history->user?->name ?? 'Guest',
                    'Question'    => $history->question?->id,
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
            'Question',
            'Answer',
            'Correct',
            'Attempts',
            'Timestamp',
        ];
    }
}
