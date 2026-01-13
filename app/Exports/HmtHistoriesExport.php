<?php

namespace App\Exports;

use App\Models\HmtHistory;
use App\Models\HmtSession;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HmtHistoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // =========================
        // 1. Ambil latest session per user (attempt tertinggi)
        // =========================
        $latestSessions = HmtSession::selectRaw('MAX(attempts) as max_attempts, user_id')
            ->groupBy('user_id')
            ->get()
            ->map(
                fn($s) =>
                HmtSession::where('user_id', $s->user_id)
                    ->where('attempts', $s->max_attempts)
                    ->value('id')
            )
            ->filter()
            ->values();

        // =========================
        // 2. Ambil SEMUA history dari latest session tersebut
        // =========================
        $histories = HmtHistory::with(['question', 'session.user'])
            ->whereIn('session_id', $latestSessions)
            ->get();

        // =========================
        // 3. Format data export
        // =========================
        return $histories->map(function ($history) {
            $session = $history->session;
            $user = $session?->user;

            $answerIndex = $history->answer_index;
            $correctIndex = $history->question?->correct_index;

            return [
                'Session ID' => $history->session_id,
                'User ID' => $user?->id,
                'User' => $user?->name ?? 'Guest',
                'Email' => $user?->email ?? '-',
                'Attempts' => $session?->attempts,
                'Question ID' => $history->question?->id,
                'Answer Index' => $answerIndex !== null ? (string) $answerIndex : 'NULL',
                'Correct Index' => $correctIndex !== null ? (string) $correctIndex : 'NULL',
                'Correct?' => $answerIndex && $answerIndex === $correctIndex ? 'TRUE' : 'FALSE',

                'Answered At' => $history->answered_at !== null ? $history->answered_at
                        ?->format('Y-m-d H:i:s.v') : 'NULL',

                'Session Started' => $session->started_at
                        ?->format('Y-m-d H:i:s.v'),

                'Session Finished' => $session->finished_at
                        ?->format('Y--d H:i:s.v'),
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
