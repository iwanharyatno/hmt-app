@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card Total Partisipan Learning Style -->
        <div class="bg-white p-6 rounded-lg shadow flex items-center justify-between">
            <div>
                <h2 class="text-gray-600 text-sm font-medium">Total Partisipan Learning Style</h2>
                <p class="text-4xl font-bold text-orange-600 mt-2">{{ $totalLearningStyle }}</p>
            </div>
            <div class="bg-orange-100 p-4 rounded-full">
                <i class="fas fa-brain text-orange-500 text-3xl"></i>
            </div>
        </div>

        <!-- Card Total Partisipan HMT -->
        <div class="bg-white p-6 rounded-lg shadow flex items-center justify-between">
            <div>
                <h2 class="text-gray-600 text-sm font-medium">Total Partisipan HMT</h2>
                <p class="text-4xl font-bold text-pink-600 mt-2">{{ $totalHmtParticipants }}</p>
            </div>
            <div class="bg-pink-100 p-4 rounded-full">
                <i class="fas fa-puzzle-piece text-pink-500 text-3xl"></i>
            </div>
        </div>
    </div>
@endsection
