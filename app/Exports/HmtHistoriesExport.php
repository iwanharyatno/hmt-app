<?php

namespace App\Exports;

use App\Models\HmtHistory;
use App\Models\HmtSession;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HmtHistoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil sesi terakhir per user berdasarkan kolom attempts tertinggi
        $latestSessions = HmtSession::selectRaw('MAX(attempts) as max_attempts, user_id')
            ->groupBy('user_id')
            ->get()
            ->map(function ($s) {
                return HmtSession::where('user_id', $s->user_id)
                    ->where('attempts', $s->max_attempts)
                    ->value('id');
            })
            ->filter(); // buang null

        // Ambil semua history dari sesi-sesi tersebut
        return HmtHistory::with(['question', 'session.user'])
            ->whereIn('session_id', $latestSessions)
            ->get()
            ->map(function ($history) {
                $session = $history->session;
                $user = $session?->user;

                // Ensure 0 or false are not cast to empty strings
                $answerIndex = $history->answer_index ?? 'NULL';
                $correctIndex = $history->question?->correct_index ?? 'NULL';

                // Jika null, tampilkan string 'NULL', jika 0, tetap 0
                $answerIndex = ($answerIndex === null) ? 'NULL' : (string) $answerIndex;
                $correctIndex = ($correctIndex === null) ? 'NULL' : (string) $correctIndex;

                return [
                    'Session ID'        => $history->session_id,
                    'User ID'           => $user?->id ?? null,
                    'User'              => $user?->name ?? 'Guest',
                    'Email'             => $user?->email ?? '-',
                    'Attempts'          => $session?->attempts ?? null,
                    'Question ID'       => $history->question?->id,
                    'Answer Index'      => $answerIndex,
                    'Correct Index'     => $correctIndex,
                    'Correct?'          => $history->answer_index == $history->question?->correct_index ? 'TRUE' : 'FALSE',
                    'Answered At'       => $history->answered_at?->format('Y-m-d H:i:s'),
                    'Session Started'   => $session?->started_at?->format('Y-m-d H:i:s'),
                    'Session Finished'  => $session?->finished_at?->format('Y-m-d H:i:s'),
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
