@extends('layouts.public')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 py-16">
    <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
        <h1 class="text-2xl font-bold">Daftar</h1>
        <p class="mt-1 text-sm text-gray-600">Buat akun untuk mulai ikut event atau jadi organizer.</p>

        @if ($errors->any())
            <div class="mt-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg p-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form class="mt-6 space-y-4" method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <label class="text-sm font-medium">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="text-sm font-medium">Password</label>
                <input type="password" name="password" required
                       class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="text-sm font-medium">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                       class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                Buat Akun
            </button>
        </form>

        <p class="mt-6 text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Masuk</a>
        </p>
    </div>
</div>
@endsection
