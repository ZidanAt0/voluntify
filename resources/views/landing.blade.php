@extends('layouts.public')

@section('content')
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
                {{-- HERO buttons --}}
                <div class="mt-6 flex gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="px-5 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                            Mulai Gratis
                        </a>
                        <a href="{{ route('login') }}" class="px-5 py-3 rounded-xl border border-gray-200 hover:border-indigo-500">
                            Sudah punya akun?
                        </a>
                    @endguest

                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-3 rounded-xl bg-gray-900 text-white hover:bg-gray-800">
                            Buka Dashboard
                        </a>
                    @endauth
                </div>

                <dl class="mt-8 grid grid-cols-3 gap-4 text-center">
                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        <dt class="text-xs uppercase text-gray-500">Event Aktif</dt>
                        <dd class="text-xl font-semibold">0</dd>
                    </div>
                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        <dt class="text-xs uppercase text-gray-500">Relawan</dt>
                        <dd class="text-xl font-semibold">0</dd>
                    </div>
                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        <dt class="text-xs uppercase text-gray-500">Organizer</dt>
                        <dd class="text-xl font-semibold">0</dd>
                    </div>
                </dl>
            </div>

            <div class="lg:justify-self-end">
                <div class="bg-white rounded-2xl shadow-lg p-5 sm:p-6">
                    <h3 class="font-semibold">Event unggulan minggu ini</h3>
                    <div class="mt-4 grid sm:grid-cols-2 gap-4">
                        @foreach ([
                            ['title'=>'Beach Cleanup Day','tag'=>'Volunteer','date'=>'18 Nov','place'=>'Lampung'],
                            ['title'=>'Tech Meetup UI/UX','tag'=>'Meetup','date'=>'20 Nov','place'=>'Bandar Lampung'],
                            ['title'=>'Tree Planting','tag'=>'Volunteer','date'=>'23 Nov','place'=>'Metro'],
                            ['title'=>'Career Talk','tag'=>'Seminar','date'=>'25 Nov','place'=>'Online'],
                        ] as $e)
                        <div class="border rounded-xl p-4 hover:shadow-md transition">
                            <div class="text-xs text-indigo-600 font-semibold">{{ $e['tag'] }}</div>
                            <div class="mt-1 font-medium">{{ $e['title'] }}</div>
                            <div class="mt-2 text-sm text-gray-600 flex items-center justify-between">
                                <span>{{ $e['place'] }}</span>
                                <span class="text-gray-500">{{ $e['date'] }}</span>
                            </div>
                            <button class="mt-3 w-full px-3 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-800 text-sm">Lihat detail</button>
                        </div>
                        @endforeach
                    </div>
                    <a class="mt-4 inline-flex items-center text-sm text-indigo-600 hover:underline" href="#">Lihat semua event →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
    <h2 class="text-xl font-semibold">Mengapa pakai Voluntify?</h2>
    <div class="mt-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ([
            ['t'=>'Daftar Cepat','d'=>'Form singkat, langsung dapat tiket/slot volunteer.'],
            ['t'=>'QR Check-in','d'=>'Proses hadir di lokasi jadi cepat & rapi.'],
            ['t'=>'Sertifikat Otomatis','d'=>'Unduh sertifikat pasca-event sekali klik.'],
            ['t'=>'Ulasan Transparan','d'=>'Lihat rating & testimoni peserta sebelumnya.'],
        ] as $f)
        <div class="bg-white rounded-xl p-5 shadow-sm">
            <div class="text-indigo-600 font-semibold">{{ $f['t'] }}</div>
            <p class="mt-2 text-sm text-gray-600">{{ $f['d'] }}</p>
        </div>
        @endforeach
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 pb-16">
    <div class="rounded-2xl bg-indigo-600 text-white p-8 sm:p-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-semibold">Punya komunitas atau acara?</h3>
            <p class="text-indigo-100">Daftar sebagai organizer dan mulailah rekrut relawan atau jual tiket.</p>
        </div>
        <a href="{{ route('register') }}" class="px-5 py-3 rounded-xl bg-white text-indigo-700 hover:bg-indigo-50 font-medium">Daftar Organizer</a>
    </div>
</section>
@endsection
