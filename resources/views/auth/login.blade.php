@extends('layouts.public')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 py-16">
    <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
        <h1 class="text-2xl font-bold">Masuk</h1>
        <p class="mt-1 text-sm text-gray-600">Selamat datang kembali 👋</p>

        @if (session('status'))
            <div class="mt-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg p-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form class="mt-6 space-y-4" method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="text-sm font-medium">Password</label>
                <input type="password" name="password" required
                       class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    <span>Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-indigo-600 hover:underline" href="{{ route('password.request') }}">Lupa password?</a>
                @endif
            </div>

            <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                Masuk
            </button>
        </form>

        <p class="mt-6 text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection
