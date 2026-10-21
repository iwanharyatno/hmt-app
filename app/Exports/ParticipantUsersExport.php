<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipantUsersExport implements FromCollection, WithHeadings
{
    public function __construct(private Collection $users) {}

    public function collection()
    {
        return $this->users->map(fn (User $user) => [
            'User ID' => $user->id,
            'Nama' => $user->name,
            'Email' => $user->email,
            'Telepon' => $user->phone ?? '-',
            'Jenis Kelamin' => match ($user->gender) {
                'L' => 'Laki-laki',
                'P' => 'Perempuan',
                default => '-',
            },
            'Tanggal Lahir' => $user->birthDate
                ? Carbon::parse($user->birthDate)->format('Y-m-d')
                : '-',
            'Institusi' => $user->institution ?? '-',
            'Tanggal Daftar' => $user->created_at?->format('Y-m-d H:i:s') ?? '-',
        ]);
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Nama',
            'Email',
            'Telepon',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Institusi',
            'Tanggal Daftar',
        ];
    }
}
