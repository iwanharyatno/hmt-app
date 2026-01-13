<?php

namespace App\Exports;

use App\Models\HmtHistory;
use App\Models\HmtSession;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HmtHistorySingleExport implements FromCollection, WithHeadings
{
    private int $sessionId;

    public function __construct(int $sessionId)
    {
        $this->sessionId = $sessionId;
    }

    public function collection()
    {
        // =========================
        // 1. Ambil session + user
        // =========================
        $session = HmtSession::with('user')->findOrFail($this->sessionId);
        $user = $session->user;

        // =========================
        // 2. Ambil SEMUA history dalam session ini
        // =========================
        $histories = HmtHistory::with('question')
            ->where('session_id', $this->sessionId)
            ->orderBy('answered_at') // optional, tapi sangat masuk akal
            ->get();

        // =========================
        // 3. Format data export
        // =========================
        return $histories->map(function ($history) use ($session, $user) {
            $answerIndex = $history->answer_index;
            $correctIndex = $history->question?->correct_index;

            return [
                'Session ID' => $history->session_id,
                'User ID' => $user?->id,
                'User' => $user?->name ?? 'Guest',
                'Email' => $user?->email ?? '-',
                'Attempts' => $session->attempts,
                'Question ID' => $history->question?->id,
                'Answer Index' => $answerIndex !== null ? (string) $answerIndex : 'NULL',
                'Correct Index' => $correctIndex !== null ? (string) $correctIndex : 'NULL',
                'Correct?' => $answerIndex && $answerIndex === $correctIndex ? 'TRUE' : 'FALSE',

                'Answered At' => $history->answered_at !== null ? $history->answered_at
                        ?->format('Y-m-d H:i:s.v') : 'NULL',

                'Session Started' => $session->started_at
                        ?->format('Y-m-d H:i:s.v'),

                'Session Finished' => $session->finished_at
                        ?->format('Y-m-d H:i:s.v'),
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
