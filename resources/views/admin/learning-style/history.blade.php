@extends('layouts.admin')

@section('title', 'Learning Style Histories')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-orange-600">History Learning Style Volunteer</h1>

        <a href="{{ route('admin.learning-style.export') }}"
            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-center">
            <i class="fas fa-file-csv"></i> Export Semua Hasil (CSV)
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-sm">
            <thead class="bg-orange-100 text-orange-700">
                <tr>
                    <th class="py-3 px-4 text-left">User Id</th>
                    <th class="py-3 px-4 text-left">User</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Pemrosesan</th>
                    <th class="py-3 px-4 text-left">Persepsi</th>
                    <th class="py-3 px-4 text-left">Input</th>
                    <th class="py-3 px-4 text-left">Pemahaman</th>
                    <th class="py-3 px-4 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($participants as $i => $p)
                    <tr class="border-t hover:bg-orange-50">
                        <td class="py-3 px-4">{{ $p['user']->id }}</td>
                        <td class="py-3 px-4">{{ $p['user']->name }}</td>
                        <td class="py-3 px-4">{{ $p['user']->email }}</td>
                        <td class="py-3 px-4">{{ $p['mapped']['Pemrosesan'] }}</td>
                        <td class="py-3 px-4">{{ $p['mapped']['Persepsi'] }}</td>
                        <td class="py-3 px-4">{{ $p['mapped']['Input'] }}</td>
                        <td class="py-3 px-4">{{ $p['mapped']['Pemahaman'] }}</td>
                        <td class="py-3 px-4 text-gray-500">{{ $p['created_at'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-center text-gray-500">Belum ada data hasil Learning Style.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
