@extends('layouts.dashboard')

@section('content')

{{-- HERO BLUE GRADIENT --}}
<div class="overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-blue-500 to-blue-400 text-white shadow-xl relative">
    <div class="absolute inset-0 bg-white/10 backdrop-blur-[1.5px] opacity-30"></div>

    <div class="relative p-7 sm:p-10 flex items-center justify-between">
        <div class="flex items-center gap-6">
            <img src="{{ auth()->user()->avatar_url }}"
                 class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white/20 shadow-xl">

            <div>
                <p class="text-white/80 text-sm">Dashboard Organizer</p>
                <h1 class="text-4xl font-bold tracking-tight">{{ auth()->user()->name }}</h1>
                <p class="text-white/80 text-sm mt-2">Kelola event & relawan dengan tampilan baru.</p>
            </div>
        </div>

        <a href="{{ route('organizer.events.create') }}"
           class="hidden sm:block px-6 py-3 rounded-xl bg-white/90 text-blue-700 font-semibold shadow hover:bg-white transition">
            + Buat Event
        </a>
    </div>
</div>

{{-- KPI CARDS BLUE THEME --}}
<div class="grid sm:grid-cols-5 gap-6 mt-10">
    @php
        $stats = [
            ['label' => 'Event', 'value' => $totalEvents, 'color' => 'text-blue-600'],
            ['label' => 'Pendaftar', 'value' => $totalRegistrants, 'color' => 'text-blue-500'],
            ['label' => 'Disetujui', 'value' => $totalApproved, 'color' => 'text-indigo-600'],
            ['label' => 'Check-in', 'value' => $totalCheckedIn, 'color' => 'text-indigo-500'],
            ['label' => 'Dibatalkan', 'value' => $totalCancelled, 'color' => 'text-red-600'],
        ];
    @endphp

    @foreach ($stats as $s)
        <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-blue-100 p-6 hover:shadow-2xl transition">
            <p class="text-sm text-gray-600">{{ $s['label'] }}</p>
            <div class="text-4xl font-bold mt-1 {{ $s['color'] }}">{{ $s['value'] }}</div>
        </div>
    @endforeach
</div>

{{-- SUMMARY TABLE BLUE --}}
<div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-blue-100 overflow-hidden mt-10">
    <div class="p-6 border-b bg-blue-50/60 backdrop-blur">
        <h2 class="font-semibold text-xl">📊 Ringkasan Per Event</h2>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-blue-100/60">
            <tr>
                <th class="px-5 py-3 text-left font-semibold">Event</th>
                <th class="px-5 py-3 text-center font-semibold">Total</th>
                <th class="px-5 py-3 text-center font-semibold">Disetujui</th>
                <th class="px-5 py-3 text-center font-semibold">Check-in</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-blue-100">
            @foreach ($eventStats as $e)
                <tr class="hover:bg-blue-50/50 transition">
                    <td class="px-5 py-3 font-medium">{{ $e->title }}</td>
                    <td class="px-5 py-3 text-center">{{ $e->registrations_count }}</td>
                    <td class="px-5 py-3 text-center">{{ $e->approved_count }}</td>
                    <td class="px-5 py-3 text-center">{{ $e->checkin_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- CONTENT GRID --}}
<div class="mt-10 grid lg:grid-cols-3 gap-8">

    {{-- LEFT SECTION --}}
    <div class="lg:col-span-2 space-y-8">

        {{-- UPCOMING EVENTS BLUE --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-blue-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xl font-semibold">📅 Event Akan Datang</h2>
                <a href="{{ route('organizer.events.index') }}" class="text-sm text-blue-700 hover:underline">
                    Kelola
                </a>
            </div>

            @if ($upcomingEvents->isEmpty())
                <p class="text-gray-600">Belum ada event aktif.</p>
            @else
                <div class="mt-4 divide-y divide-blue-100">
                    @foreach ($upcomingEvents as $e)
                        <div class="py-4 flex items-start gap-4 rounded-xl hover:bg-blue-50/50 transition px-2">
                            <img src="{{ $e->banner_url }}"
                                 class="w-28 h-20 object-cover rounded-xl shadow ring-1 ring-blue-200">

                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $e->title }}</p>
                                <p class="text-sm text-gray-500">{{ $e->date_human }} • {{ $e->city }}</p>
                            </div>

                            <a href="{{ route('organizer.events.edit', $e) }}"
                               class="text-sm px-4 py-2 rounded-xl border border-blue-200 text-blue-700 hover:bg-blue-50 transition">
                                Edit
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- RECENT REGISTRATIONS BLUE --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-blue-100 p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold">🧍‍♂ Pendaftar Terbaru</h2>
                <a href="{{ route('organizer.events.index') }}" class="text-sm text-blue-700 hover:underline">
                    Lihat semua
                </a>
            </div>

            @if ($recentRegistrations->isEmpty())
                <p class="text-gray-600 mt-3">Belum ada pendaftar baru.</p>
            @else
                <ul class="mt-4 space-y-4">
                    @foreach ($recentRegistrations as $r)
                        <li class="p-4 bg-white rounded-xl shadow-sm ring-1 ring-blue-100 hover:shadow-md transition flex justify-between items-center">
                            <div>
                                <p class="font-medium">{{ $r->user->name }}</p>
                                <p class="text-sm text-gray-500">
                                    Mendaftar ke: {{ $r->event->title }}
                                </p>
                            </div>

                            <span class="px-3 py-1 text-xs rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200">
                                {{ ucfirst($r->status) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- RIGHT SIDEBAR BLUE --}}
    <aside>
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-blue-100 p-6">
            <h2 class="text-xl font-semibold">⚡ Aksi Cepat</h2>

            <div class="mt-5 grid gap-4">
                <a href="{{ route('organizer.events.create') }}"
                   class="px-4 py-3 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition shadow-sm">
                    + Buat Event
                </a>

                <a href="{{ route('organizer.events.index') }}"
                   class="px-4 py-3 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition shadow-sm">
                    Kelola Event
                </a>

                <a href="#"
                   class="px-4 py-3 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition shadow-sm">
                    Lihat Pendaftar
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="px-4 py-3 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition shadow-sm">
                    Edit Profil
                </a>

                <a href="{{ route('organizer.checkin.index') }}"
                   class="px-4 py-3 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition shadow-sm">
                    Check-in Peserta
                </a>
            </div>
        </div>
    </aside>
</div>

@endsection