<?php

namespace App\Exports;

use App\Models\HmtHistory;
use App\Models\HmtSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HmtHistoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil sesi terakhir per user (berdasarkan attempts tertinggi)
        $latestSessions = HmtSession::selectRaw('MAX(attempts) as max_attempts, user_id')
            ->groupBy('user_id')
            ->get()
            ->map(function ($s) {
                return HmtSession::where('user_id', $s->user_id)
                    ->where('attempts', $s->max_attempts)
                    ->value('id');
            })
            ->filter();
        Log::debug($latestSessions);

        // Ambil ID history terakhir per question_id dalam setiap session
        $latestHistoriesIds = HmtHistory::select(DB::raw('MAX(id) as id'))
            ->whereIn('session_id', $latestSessions)
            ->groupBy('session_id', 'question_id')
            ->pluck('id');
        Log::debug($latestHistoriesIds);

        // Ambil detail history dari ID-ID tersebut
        $histories = HmtHistory::with(['question', 'session.user'])
            ->whereIn('id', $latestHistoriesIds)
            ->get();    
        Log::debug($histories);

        // Format data untuk ekspor
        return $histories->map(function ($history) {
            $session = $history->session;
            $user = $session?->user;

            $answerIndex = $history->answer_index;
            $correctIndex = $history->question?->correct_index;

            // Pastikan nilai 0 tetap muncul (bukan kosong)
            $answerIndex = $answerIndex ?? 'NULL';
            $correctIndex = $correctIndex ?? 'NULL';
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
