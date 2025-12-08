@extends('layouts.dashboard')

@section('content')
<div class="bg-gradient-to-r from-indigo-600 to-sky-500 text-white rounded-3xl p-8 shadow-lg">
    <div class="text-sm text-white/80">Admin Area</div>
    <h1 class="text-3xl font-semibold mt-1">Admin Dashboard</h1>
    <p class="text-white/80 mt-2">Pantau metrik dan moderasi konten.</p>
</div>

<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <div class="text-sm text-gray-500">Users</div>
        <div class="text-3xl font-bold mt-2">{{ $kpi['users'] ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <div class="text-sm text-gray-500">Organizers</div>
        <div class="text-3xl font-bold mt-2">{{ $kpi['organizers'] ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <div class="text-sm text-gray-500">Event Aktif</div>
        <div class="text-3xl font-bold mt-2">{{ $kpi['events_active'] ?? 0 }}</div>
        <p class="text-xs text-gray-500 mt-1">Pending: {{ $kpi['events_pending'] ?? 0 }}</p>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Event Terbaru</h2>
            <span class="text-xs text-gray-500">Applied: {{ $kpi['applied'] ?? 0 }} · Approved: {{ $kpi['approved'] ?? 0 }}</span>
        </div>
        <div class="mt-4 space-y-3">
            @forelse($latestEvents as $event)
                <div class="flex items-start gap-3">
                    <div class="w-12 h-12 rounded-lg overflow-hidden ring-1 ring-gray-200">
                        <img src="{{ $event->banner_url }}" class="w-full h-full object-cover" alt="">
                    </div>
                    <div>
                        <div class="font-semibold">{{ $event->title }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $event->category->name ?? 'Tanpa kategori' }} · {{ $event->date_human }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-500">Belum ada event.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <h2 class="text-lg font-semibold">User Terbaru</h2>
        <div class="mt-4 space-y-3">
            @forelse($latestUsers as $user)
                <div class="flex items-center gap-3">
                    <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-200" alt="">
                    <div>
                        <div class="font-semibold">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-500">Belum ada user.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
