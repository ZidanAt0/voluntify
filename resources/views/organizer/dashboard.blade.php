@extends('layouts.dashboard')

@section('content')
    {{-- HERO --}}
    <div class="overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-500 text-white">
        <div class="p-6 sm:p-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ auth()->user()->avatar_url }}" class="w-14 h-14 rounded-full object-cover ring-4 ring-white/20"
                    alt="">
                <div>
                    <div class="text-white/90 text-sm">Dashboard Organizer,</div>
                    <h1 class="text-2xl sm:text-3xl font-semibold">{{ auth()->user()->name }}</h1>
                    <p class="text-white/80 text-sm mt-1">Kelola event & relawan kamu di sini.</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-6">
                <a href="{{ route('organizer.events.create') }}"
                    class="px-4 py-2 rounded-xl bg-white text-emerald-700 hover:bg-gray-50">
                    + Buat Event
                </a>
            </div>
        </div>
    </div>

    {{-- KPI --}}
    <div class="grid sm:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 p-4">
            <div class="text-sm text-gray-600">Event</div>
            <div class="text-2xl font-bold">{{ $totalEvents }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 p-4">
            <div class="text-sm text-gray-600">Pendaftar</div>
            <div class="text-2xl font-bold">{{ $totalRegistrants }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 p-4">
            <div class="text-sm text-gray-600">Disetujui</div>
            <div class="text-2xl font-bold text-green-600">{{ $totalApproved }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 p-4">
            <div class="text-sm text-gray-600">Check-in</div>
            <div class="text-2xl font-bold text-indigo-600">{{ $totalCheckedIn }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 p-4">
            <div class="text-sm text-gray-600">Dibatalkan</div>
            <div class="text-2xl font-bold text-red-600">{{ $totalCancelled }}</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 overflow-hidden">
        <div class="p-4 font-semibold text-lg">Ringkasan Per Event</div>
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Event</th>
                    <th class="px-4 py-2 text-center">Total</th>
                    <th class="px-4 py-2 text-center">Disetujui</th>
                    <th class="px-4 py-2 text-center">Check-in</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($eventStats as $e)
                    <tr>
                        <td class="px-4 py-2">{{ $e->title }}</td>
                        <td class="px-4 py-2 text-center">{{ $e->registrations_count }}</td>
                        <td class="px-4 py-2 text-center">{{ $e->approved_count }}</td>
                        <td class="px-4 py-2 text-center">{{ $e->checkin_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 grid lg:grid-cols-3 gap-6">
        {{-- COL 1-2 --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- UPCOMING EVENTS --}}
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Event Akan Datang</h2>
                    <a href="{{ route('organizer.events.index') }}"
                        class="text-sm text-emerald-700 hover:underline">Kelola</a>
                </div>

                @if ($upcomingEvents->isEmpty())
                    <p class="text-gray-600 mt-3">Belum ada event aktif.</p>
                @else
                    <div class="mt-4 divide-y">
                        @foreach ($upcomingEvents as $e)
                            <div class="py-3 flex items-start gap-4">
                                <img src="{{ $e->banner_url }}"
                                    class="w-20 h-16 object-cover rounded-lg ring-1 ring-gray-200" alt="">
                                <div class="flex-1">
                                    <div class="font-medium">{{ $e->title }}</div>
                                    <div class="text-sm text-gray-600">
                                        {{ $e->date_human }} · {{ $e->city }}
                                    </div>
                                </div>
                                <a href="{{ route('organizer.events.edit', $e) }}"
                                    class="text-sm px-3 py-1.5 rounded-lg border border-emerald-200 text-emerald-700 hover:bg-emerald-50">
                                    Edit
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- REGISTRATION ACTIVITY --}}
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Pendaftar Terbaru</h2>
                    <a href="{{ route('organizer.events.index') }}" class="text-sm text-emerald-700 hover:underline">Lihat
                        semua</a>
                </div>

                @if ($recentRegistrations->isEmpty())
                    <p class="text-gray-600 mt-3">Belum ada pendaftaran masuk.</p>
                @else
                    <ul class="mt-3 space-y-2">
                        @foreach ($recentRegistrations as $r)
                            <li class="p-3 rounded-xl ring-1 ring-gray-200 flex items-center justify-between">
                                <div>
                                    <div class="font-medium">{{ $r->user->name }}</div>
                                    <div class="text-sm text-gray-600">
                                        Mendaftar ke: {{ $r->event->title }}
                                    </div>
                                </div>
                                <span
                                    class="ml-4 inline-flex px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- COL 3 --}}
        <aside class="space-y-6">
            {{-- QUICK LINKS --}}
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
                <h2 class="text-lg font-semibold">Aksi Cepat</h2>
                <div class="mt-3 grid gap-2">
                    <a href="{{ route('organizer.events.create') }}"
                        class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">+ Buat Event</a>
                    <a href="{{ route('organizer.events.index') }}"
                        class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Kelola Event</a>
                    <a href="#" class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Lihat
                        Pendaftar</a>
                    <a href="{{ route('profile.edit') }}"
                        class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Edit Profil</a>
                    <a href="{{ route('organizer.checkin.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100">
                        Check-in Peserta
                    </a>

                </div>
            </div>
        </aside>
    </div>
@endsection
