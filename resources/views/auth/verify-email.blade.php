@extends('layouts.auth')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-lg ring-1 ring-gray-200 overflow-hidden">
            {{-- Header dengan ilustrasi --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8 text-center">
                <div class="mx-auto w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Verifikasi Email Anda</h1>
                <p class="mt-2 text-indigo-100">Langkah terakhir sebelum memulai!</p>
            </div>

            {{-- Content --}}
            <div class="px-6 py-8">
                {{-- Success notification --}}
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="font-medium text-green-900">Email Berhasil Dikirim!</h3>
                                <p class="mt-1 text-sm text-green-700">
                                    Link verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Main message --}}
                <div class="text-center mb-8">
                    <p class="text-gray-700 text-lg">
                        Terima kasih sudah mendaftar di <span class="font-semibold text-indigo-600">Voluntify</span>!
                    </p>
                    <p class="mt-3 text-gray-600">
                        Kami telah mengirimkan link verifikasi ke email Anda di:
                    </p>
                    <p class="mt-2 text-xl font-semibold text-gray-900">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                {{-- Instructions --}}
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Langkah Selanjutnya:</h3>
                    <ol class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-semibold text-xs flex-shrink-0">1</span>
                            <span>Buka inbox email Anda (jangan lupa cek folder spam/junk)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-semibold text-xs flex-shrink-0">2</span>
                            <span>Cari email dari Voluntify dengan subject "Verify Email Address"</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-semibold text-xs flex-shrink-0">3</span>
                            <span>Klik tombol "Verify Email Address" di dalam email</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-semibold text-xs flex-shrink-0">4</span>
                            <span>Anda akan diarahkan kembali dan bisa mulai menggunakan Voluntify!</span>
                        </li>
                    </ol>
                </div>

                {{-- Resend Section --}}
                <div class="border-t border-gray-200 pt-6">
                    <p class="text-center text-gray-600 mb-4">Tidak menerima email?</p>
                    <form method="POST" action="{{ route('verification.send') }}" class="text-center">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition-colors font-medium">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>

                    {{-- Help text --}}
                    <div class="mt-6 text-center text-sm text-gray-500">
                        <p>Butuh bantuan? Email salah?</p>
                        <p class="mt-1">
                            Hubungi kami di
                            <a href="mailto:support@voluntify.com" class="text-indigo-600 hover:underline">support@voluntify.com</a>
                        </p>
                    </div>

                    {{-- Logout button --}}
                    <div class="mt-6 text-center">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
