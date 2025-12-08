@extends('layouts.public')

@section('content')
    {{-- Hero Section --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-purple-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-24">
            <div class="grid lg:grid-cols-2 gap-10 items-center">
                <div>
                    <h1 class="text-3xl sm:text-5xl font-bold leading-tight">
                        Temukan <span class="text-indigo-600">Event & Volunteer</span> yang tepat untukmu
                    </h1>
                    <p class="mt-4 text-gray-600 sm:text-lg">
                        Jelajahi kegiatan sosial, komunitas, dan acara profesional. Daftar cepat, check-in QR,
                        sertifikat otomatis, dan ulasan transparan.
                    </p>

                    {{-- CTA buttons --}}
                    <div class="mt-6 flex flex-wrap gap-3">
                        @guest
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 font-medium transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                Mulai Gratis
                            </a>
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 text-gray-700 hover:border-indigo-500 hover:text-indigo-600 font-medium transition-colors">
                                Sudah punya akun?
                            </a>
                        @endguest

                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gray-900 text-white hover:bg-gray-800 font-medium transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                Buka Dashboard
                            </a>
                        @endauth

                        <a href="{{ route('events.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 text-gray-700 hover:border-indigo-500 hover:text-indigo-600 font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Explore Event
                        </a>
                    </div>

                    {{-- Statistics --}}
                    <div class="mt-8 grid grid-cols-3 gap-4">
                        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <dt class="text-xs uppercase text-gray-500 font-medium">Event Aktif</dt>
                                    <dd class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($stats['events']) }}</dd>
                                </div>
                                <div class="p-2 rounded-lg bg-indigo-50">
                                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <dt class="text-xs uppercase text-gray-500 font-medium">Relawan</dt>
                                    <dd class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($stats['volunteers']) }}</dd>
                                </div>
                                <div class="p-2 rounded-lg bg-green-50">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <dt class="text-xs uppercase text-gray-500 font-medium">Organizer</dt>
                                    <dd class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($stats['organizers']) }}</dd>
                                </div>
                                <div class="p-2 rounded-lg bg-purple-50">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Featured Events --}}
                <div class="lg:justify-self-end w-full">
                    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-gray-200 p-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Event Mendatang</h3>
                            <a href="{{ route('events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                Lihat Semua →
                            </a>
                        </div>

                        @if($featuredEvents->count() > 0)
                            <div class="space-y-4">
                                @foreach ($featuredEvents as $event)
                                    <a href="{{ route('events.show', $event->slug) }}"
                                       class="block border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-indigo-200 transition-all group">
                                        <div class="flex items-start gap-3">
                                            <img src="{{ $event->banner_url }}"
                                                 alt="{{ $event->title }}"
                                                 class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                            <div class="flex-1 min-w-0">
                                                @if($event->category)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200">
                                                        {{ $event->category->name }}
                                                    </span>
                                                @endif
                                                <h4 class="mt-1 font-medium text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                                                    {{ $event->title }}
                                                </h4>
                                                <div class="mt-2 flex items-center gap-3 text-xs text-gray-600">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        {{ $event->starts_at->locale('id')->format('d M Y') }}
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        {{ $event->city ?? 'Online' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-sm">Belum ada event mendatang</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories Section --}}
    @if($categories->count() > 0)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Kategori Populer</h2>
            <a href="{{ route('events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('events.index', ['category' => $category->slug]) }}"
                   class="group bg-white rounded-xl p-5 shadow-sm ring-1 ring-gray-200 hover:shadow-md hover:ring-indigo-200 transition-all text-center">
                    <div class="w-12 h-12 mx-auto rounded-full bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center transition-colors">
                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="mt-3 font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors text-sm">
                        {{ $category->name }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ $category->events_count }} event
                    </p>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Features Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-gray-900">Mengapa pakai Voluntify?</h2>
            <p class="mt-2 text-gray-600">Platform volunteer terbaik untuk komunitas dan organizer</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 't' => 'Daftar Cepat', 'd' => 'Form singkat, langsung dapat tiket/slot volunteer.', 'color' => 'indigo'],
                ['icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', 't' => 'Code Check-in', 'd' => 'Proses hadir di lokasi jadi cepat & rapi.', 'color' => 'green'],
                ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 't' => 'Sertifikat Otomatis', 'd' => 'Unduh sertifikat pasca-event sekali klik.', 'color' => 'purple'],
                ['icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 't' => 'Ulasan Transparan', 'd' => 'Lihat rating & testimoni peserta sebelumnya.', 'color' => 'yellow']
            ] as $f)
                <div class="bg-white rounded-xl p-6 shadow-sm ring-1 ring-gray-200 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-lg bg-{{ $f['color'] }}-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-{{ $f['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $f['t'] }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ $f['d'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 pb-16">
        <div class="rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-8 sm:p-10 shadow-xl">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex-1">
                    <h3 class="text-2xl sm:text-3xl font-bold">Punya komunitas atau acara?</h3>
                    <p class="mt-2 text-indigo-100 text-lg">Daftar sebagai organizer dan mulailah rekrut relawan atau kelola event Anda.</p>
                    <ul class="mt-4 space-y-2 text-sm text-indigo-100">
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Kelola event dengan mudah</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Sistem check-in otomatis dengan QR</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Generate sertifikat secara otomatis</span>
                        </li>
                    </ul>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-indigo-700 hover:bg-indigo-50 font-semibold text-lg shadow-lg hover:shadow-xl transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        Daftar Organizer
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
