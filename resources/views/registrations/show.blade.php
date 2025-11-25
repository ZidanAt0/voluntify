@extends('layouts.dashboard')

@section('content')
@if (session('status'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
        {{ session('status') }}
    </div>
@endif

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <div class="flex items-start gap-4">
            <img src="{{ $registration->event->banner_url }}" class="w-40 h-24 object-cover rounded-lg" alt="">
            <div>
                <h1 class="text-2xl font-bold">{{ $registration->event->title }}</h1>
                <div class="text-sm text-gray-600 mt-1">
                    {{ $registration->event->date_human }} · {{ $registration->event->time_range }} · {{ $registration->event->location_human }}
                </div>
                <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full ring-1 bg-indigo-50 text-indigo-700 ring-indigo-200">
                        Status: {{ ucfirst(str_replace('_',' ',$registration->status)) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Placeholder pass/QR (akan diisi saat approved, next step) --}}
        @if($registration->status === 'approved')
            <div class="mt-6">
                <div class="text-sm text-gray-600">Tunjukkan pass/QR ini saat check-in:</div>
                <div class="mt-2 italic text-gray-500">[QR akan muncul di langkah berikut]</div>
            </div>
        @endif
    </div>

    <aside class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6 space-y-4">
        @if($registration->cancellable())
            <form method="POST" action="{{ route('registrations.cancel', $registration) }}" onsubmit="return confirm('Batalkan pendaftaran?');">
                @csrf @method('DELETE')
                <button class="w-full px-4 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-700">
                    Batalkan Pendaftaran
                </button>
            </form>
        @else
            <button disabled class="w-full px-4 py-2.5 rounded-xl bg-gray-200 text-gray-600 cursor-not-allowed">
                Tidak dapat dibatalkan
            </button>
        @endif

        <a href="{{ route('events.show', $registration->event->slug) }}"
           class="block text-center w-full px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50">
           Lihat Detail Event
        </a>
        <a href="{{ route('registrations.index') }}"
           class="block text-center w-full px-4 py-2.5 rounded-xl border border-indigo-200 text-indigo-700 hover:bg-indigo-50">
           Kembali ke Pendaftaran Saya
        </a>
    </aside>
</div>
@endsection
