@extends('layouts.dashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Statistik Kehadiran</h1>

    {{-- KPI --}}
    <div class="grid sm:grid-cols-5 gap-4 mb-8">

        <div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-600">Total Event</p>
            <p class="text-2xl font-bold">{{ $totalEvents }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-600">Total Pendaftar</p>
            <p class="text-2xl font-bold">{{ $totalRegistrants }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-600">Disetujui</p>
            <p class="text-2xl font-bold text-green-600">{{ $totalApproved }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-600">Check-in</p>
            <p class="text-2xl font-bold text-indigo-600">{{ $totalCheckedIn }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-600">Dibatalkan</p>
            <p class="text-2xl font-bold text-red-600">{{ $totalCancelled }}</p>
        </div>

    </div>
@endsection
