@extends('layouts.admin')

@section('title', 'HMT Quiz')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-md">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-orange-600">History Volunteer HMT</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.hmt.histories.export') }}"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-center">
                    <i class="fas fa-file-csv"></i> Export Semua Jawaban (CSV)
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-orange-100 text-orange-700">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">User</th>
                        <th class="py-3 px-4 text-left">Percobaan</th>
                        <th class="py-3 px-4 text-left">Mulai</th>
                        <th class="py-3 px-4 text-left">Selesai</th>
                        <th class="py-3 px-4 text-left">Durasi</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($sessions as $i => $session)
                        <tr class="border-t hover:bg-orange-50">
                            <td class="py-3 px-4">
                                {{ $loop->iteration + ($sessions->currentPage() - 1) * $sessions->perPage() }}</td>
                            <td class="py-3 px-4">{{ $session->user->name ?? '-' }}</td>
                            <td class="py-3 px-4">{{ $session->attempts ?? '-' }}</td>
                            <td class="py-3 px-4">{{ $session->started_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td class="py-3 px-4">{{ $session->finished_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td class="py-3 px-4">
                                @if ($session->started_at && $session->finished_at)
                                    @php
                                        $start = \Carbon\Carbon::parse($session->started_at);
                                        $end = \Carbon\Carbon::parse($session->finished_at);
                                        $duration = $start->diffInMinutes($end);
                                    @endphp
                                    {{ $duration }} menit
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 space-x-2">
                                <a href="{{ route('admin.hmt.histories.show', $session->id) }}"
                                    class="inline-block px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.hmt.histories.single-export', $session->id) }}"
                                    class="inline-block px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">
                                    <i class="fas fa-file-csv"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $sessions->links() }}
        </div>
    </div>
@endsection
