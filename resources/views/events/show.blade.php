@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    {{-- Banner --}}
    <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="w-full h-60 sm:h-72 object-cover rounded-2xl">

    <div class="mt-6 grid lg:grid-cols-3 gap-6">
        {{-- Kiri: konten --}}
        <div class="lg:col-span-2">
            {{-- Status --}}
            <div class="mb-3">
                @if($event->status === 'cancelled')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-gray-700 bg-gray-100 ring-1 ring-gray-300">Dibatalkan</span>
                @elseif($event->is_closed)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-red-700 bg-red-50 ring-1 ring-red-200">Pendaftaran Ditutup</span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-green-700 bg-green-50 ring-1 ring-green-200">Open</span>
                @endif
                <span class="ml-2 text-gray-500 text-sm">❤ {{ $event->registration_count }} orang terdaftar</span>
            </div>

            <h1 class="text-3xl font-bold">{{ $event->title }}</h1>
            @if ($event->excerpt)
                <p class="text-gray-700 mt-2">{{ $event->excerpt }}</p>
            @endif

            <div class="mt-6 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
                <h2 class="font-semibold text-lg">Deskripsi Event</h2>
                <div class="prose max-w-none mt-3">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>

            {{-- (Opsional) blok manfaat/list lain bisa ditambah nanti --}}
        </div>

        {{-- Kanan: info box --}}
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5 space-y-4">
                <div class="flex items-start gap-3">
                    {{-- calendar --}}
                    <svg class="w-5 h-5 mt-0.5 text-gray-700" viewBox="0 0 24 24" fill="none">
                        <path d="M7 2v4M17 2v4M3 10h18M5 6h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <div>
                        <div class="font-medium">{{ $event->date_human }}</div>
                        <div class="text-sm text-gray-600">{{ $event->time_range }}</div>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    {{-- pin --}}
                    <svg class="w-5 h-5 mt-0.5 text-gray-700" viewBox="0 0 24 24" fill="none">
                        <path d="M12 21s7-4.35 7-10a7 7 0 1 0-14 0c0 5.65 7 10 7 10z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="11" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <div>
                        <div class="font-medium">{{ $event->location_human }}</div>
                        <div class="text-sm text-gray-600">Lokasi Event</div>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    {{-- user icon --}}
                    <svg class="w-5 h-5 mt-0.5 text-gray-700" viewBox="0 0 24 24" fill="none">
                        <path d="M12 13a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm6 8a6 6 0 0 0-12 0" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <div>
                        <div class="font-medium">Organizer</div>
                        <div class="text-sm text-gray-600">{{ $event->organizer?->name ?? '-' }}</div>
                    </div>
                </div>

                @if(!is_null($event->capacity))
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span>Kuota Peserta</span>
                            <span>{{ $event->registration_count }}/{{ $event->capacity }}</span>
                        </div>
                        <div class="mt-1 h-2 w-full rounded-full bg-gray-200 overflow-hidden">
                            <div class="h-2 bg-indigo-600" style="width: {{ $event->progress_percent }}%"></div>
                        </div>
                        <div class="mt-1 text-xs text-gray-600">
                            {{ $event->progress_percent }}% terisi
                            @if($event->capacity_left !== null)
                                · {{ $event->capacity_left }} slot tersisa
                            @endif
                        </div>
                    </div>
                @endif

                {{-- CTA / status --}}
                @if($event->status === 'cancelled' || $event->is_closed)
                    <button disabled class="w-full px-4 py-2.5 rounded-xl bg-gray-200 text-gray-600 cursor-not-allowed">
                        Pendaftaran ditutup
                    </button>
                @else
                    @auth
                        <button disabled class="w-full px-4 py-2.5 rounded-xl bg-indigo-600 text-white opacity-80 cursor-not-allowed">
                            Apply (akan diaktifkan di langkah Registrations)
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="block text-center w-full px-4 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                            Login untuk Apply
                        </a>
                    @endauth
                @endif
            </div>

            {{-- Kontak organizer (email/telepon bila ada) --}}
            @if($event->organizer?->email)
            <div class="mt-4 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
                <div class="font-semibold mb-2">Kontak Organizer</div>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M4 4h16v16H4z" stroke="currentColor" stroke-width="1.5"/><path d="M4 7h16" stroke="currentColor" stroke-width="1.5"/><circle cx="7" cy="5" r="1" fill="currentColor"/><circle cx="11" cy="5" r="1" fill="currentColor"/><circle cx="15" cy="5" r="1" fill="currentColor"/></svg>
                        <span>{{ $event->organizer->email }}</span>
                    </div>
                    {{-- Telepon belum ada di DB; bisa ditambah nanti --}}
                </div>
            </div>
            @endif
        </aside>
    </div>
</div>
@endsection
