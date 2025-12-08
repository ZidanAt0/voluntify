@extends('layouts.public')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 py-16">
    <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">

        <h1 class="text-2xl font-bold text-center">Daftar Akun</h1>
        <p class="mt-1 text-sm text-gray-600 text-center">
            Pilih peranmu lalu buat akun.
        </p>

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="mt-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg p-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-6 space-y-5" method="POST" action="{{ route('register') }}">
            @csrf

            {{-- ROLE SELECTOR --}}
            <div>
                <label class="text-sm font-medium block mb-2 text-center">
                    Daftar Sebagai
                </label>

                <div class="grid grid-cols-2 gap-3">
                    {{-- Volunteer --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="user" class="peer hidden"
                               {{ old('role', 'user') == 'user' ? 'checked' : '' }}>

                        <div class="border rounded-xl p-4 text-center flex flex-col items-center
                                    peer-checked:border-indigo-600 peer-checked:bg-indigo-50
                                    hover:border-indigo-400 transition">

                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mb-2">
                                <i class="fa-solid fa-hand-holding-heart text-indigo-600 text-xl"></i>
                            </div>

                            <div class="font-semibold">Volunteer</div>
                            <p class="text-xs text-gray-500 mt-1">
                                Ikut dan berkontribusi di berbagai kegiatan sosial.
                            </p>
                        </div>
                    </label>

                    {{-- Organizer --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="organizer" class="peer hidden"
                               {{ old('role') == 'organizer' ? 'checked' : '' }}>

                        <div class="border rounded-xl p-4 text-center flex flex-col items-center
                                    peer-checked:border-indigo-600 peer-checked:bg-indigo-50
                                    hover:border-indigo-400 transition">

                            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center mb-2">
                                <i class="fa-solid fa-clipboard-list text-amber-600 text-xl"></i>
                            </div>

                            <div class="font-semibold">Organizer</div>
                            <p class="text-xs text-gray-500 mt-1">
                                Buat, kelola, dan pantau event yang kamu selenggarakan.
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- NAMA --}}
            <div>
                <label class="text-sm font-medium">Nama</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="text-sm font-medium">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="text-sm font-medium">Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            {{-- KONFIRMASI PASSWORD --}}
            <div>
                <label class="text-sm font-medium">Konfirmasi Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            {{-- SUBMIT --}}
            <button
                type="submit"
                class="w-full px-4 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition font-semibold"
            >
                Buat Akun
            </button>
        </form>

        <p class="mt-6 text-sm text-gray-600 text-center">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">
                Masuk
            </a>
        </p>
    </div>
</div>
@endsection
