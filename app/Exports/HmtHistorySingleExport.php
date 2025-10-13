<?php

namespace App\Exports;

use App\Models\HmtHistory;
use App\Models\HmtSession;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HmtHistorySingleExport implements FromCollection, WithHeadings
{
    private $sessionId;

    public function __construct($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    public function collection()
    {
        $session = HmtSession::with('user')->findOrFail($this->sessionId);
        $user = $session->user;

        // Ambil hanya jawaban terakhir (latest) per question_id di session ini
        $latestHistoryIds = HmtHistory::select(DB::raw('MAX(id) as id'))
            ->where('session_id', $this->sessionId)
            ->groupBy('question_id')
            ->pluck('id');

        $histories = HmtHistory::with('question')
            ->whereIn('id', $latestHistoryIds)
            ->get();

        return $histories->map(function ($history) use ($session, $user) {
            // Pastikan nilai 0 / false tetap tampil
            $answerIndex = $history->answer_index;
            $correctIndex = $history->question?->correct_index;

            $answerIndex = $answerIndex ?? 'NULL';
            $correctIndex = $correctIndex ?? 'NULL';
            $answerIndex = ($answerIndex === null) ? 'NULL' : (string) $answerIndex;
            $correctIndex = ($correctIndex === null) ? 'NULL' : (string) $correctIndex;

            return [
                'Session ID'       => $history->session_id,
                'User ID'          => $user?->id ?? null,
                'User'             => $user?->name ?? 'Guest',
                'Email'            => $user?->email ?? '-',
                'Attempts'         => $session->attempts ?? null,
                'Question ID'      => $history->question?->id,
                'Answer Index'     => $answerIndex,
                'Correct Index'    => $correctIndex,
                'Correct?'         => $history->answer_index == $history->question?->correct_index ? 'TRUE' : 'FALSE',
                'Answered At'      => $history->answered_at?->format('Y-m-d H:i:s'),
                'Session Started'  => $session->started_at?->format('Y-m-d H:i:s'),
                'Session Finished' => $session->finished_at?->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Session ID',
            'User ID',
            'User',
            'Email',
            'Attempts',
            'Question ID',
            'Answer Index',
            'Correct Index',
            'Correct?',
            'Answered At',
            'Session Started',
            'Session Finished',
        ];
    }
}
