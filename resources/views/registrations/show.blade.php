@extends('layouts.dashboard')

@section('content')
{{-- Hero --}}
<div class="overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-sky-500 text-white">
  <div class="p-6 sm:p-8">
    <div class="text-white/90 text-sm">Detail Pendaftaran</div>
    <h1 class="text-2xl sm:text-3xl font-semibold">{{ $registration->event->title }}</h1>
    <p class="text-white/80 text-sm mt-1">{{ $registration->event->date_human }} · {{ $registration->event->time_range }} · {{ $registration->event->location_human }}</p>
  </div>
</div>

@if (session('status'))
  <div class="mt-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
    {{ session('status') }}
  </div>
@endif

<div class="mt-6 grid lg:grid-cols-3 gap-6">
  {{-- Left: content --}}
  <div class="lg:col-span-2 space-y-6">

    {{-- Event summary card --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
      <img src="{{ $registration->event->banner_url }}" class="w-full h-48 object-cover" loading="lazy" alt="">
      <div class="p-6">
        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-700">
          @if($registration->event->category)
            <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200 text-xs">
              {{ $registration->event->category->name }}
            </span>
          @endif
          <span class="flex items-center gap-1">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M7 2v4M17 2v4M3 10h18M5 6h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="1.5"/></svg>
            {{ $registration->event->date_human }} · {{ $registration->event->time_range }}
          </span>
          <span class="flex items-center gap-1">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 21s7-4.35 7-10a7 7 0 1 0-14 0c0 5.65 7 10 7 10z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="11" r="2.5" stroke="currentColor" stroke-width="1.5"/></svg>
            {{ $registration->event->location_human }}
          </span>
        </div>
      </div>
    </div>

    {{-- Answers (Q&A) --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
      <h2 class="text-lg font-semibold">Jawaban Formulir</h2>
      @php
        $a = $registration->answers ?? [];
        $labels = [
          'whatsapp' => 'WhatsApp',
          'city' => 'Kota/Domisili',
          'motivation' => 'Motivasi',
          'experience' => 'Pengalaman',
          'skills' => 'Keahlian',
          'emergency_name' => 'Kontak Darurat (Nama)',
          'emergency_phone'=> 'Kontak Darurat (Nomor)',
        ];
      @endphp

      <div class="mt-4 grid gap-4">
        @foreach ($labels as $key => $label)
          @continue(!array_key_exists($key, $a))
          <div class="rounded-xl ring-1 ring-gray-200 p-4">
            <div class="text-xs text-gray-500">{{ $label }}</div>
            @if($key === 'skills' && is_array($a[$key]))
              <div class="mt-1 flex flex-wrap gap-2">
                @foreach ($a[$key] as $skill)
                  <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 ring-1 ring-gray-200">{{ $skill }}</span>
                @endforeach
              </div>
            @else
              <div class="mt-1 text-gray-800 whitespace-pre-line">{{ $a[$key] ?: '-' }}</div>
            @endif
          </div>
        @endforeach

        @if(empty($a))
          <p class="text-gray-600">Belum ada jawaban tersimpan.</p>
        @endif
      </div>
    </div>

  </div>

  {{-- Right: status & actions --}}
  <aside class="space-y-4">
    @php
      $badge = [
        'applied'   => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
        'approved'  => 'bg-green-50 text-green-700 ring-green-200',
        'waitlisted'=> 'bg-blue-50 text-blue-700 ring-blue-200',
        'rejected'  => 'bg-red-50 text-red-700 ring-red-200',
        'checked_in'=> 'bg-indigo-50 text-indigo-700 ring-indigo-200',
        'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'cancelled' => 'bg-gray-100 text-gray-700 ring-gray-300',
      ][$registration->status] ?? 'bg-gray-100 text-gray-700 ring-gray-300';
    @endphp

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
      <div class="text-sm text-gray-600">Status Pendaftaran</div>
      <div class="mt-1 inline-flex items-center px-3 py-1 rounded-full ring-1 {{ $badge }}">
        {{ ucfirst(str_replace('_',' ',$registration->status)) }}
      </div>

      <dl class="mt-4 text-sm text-gray-700 space-y-1">
        <div class="flex justify-between"><dt>Didaftarkan</dt><dd>{{ $registration->applied_at?->format('d M Y H:i') }}</dd></div>
        @if($registration->cancelled_at)
        <div class="flex justify-between"><dt>Dibatalkan</dt><dd>{{ $registration->cancelled_at->format('d M Y H:i') }}</dd></div>
        @endif
      </dl>

      <div class="mt-4 space-y-2">
        @if($registration->cancellable())
          <form method="POST" action="{{ route('registrations.cancel', $registration) }}" onsubmit="return confirm('Batalkan pendaftaran?');">
            @csrf @method('DELETE')
            <button class="w-full px-4 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-700">Batalkan Pendaftaran</button>
          </form>
        @else
          <button disabled class="w-full px-4 py-2.5 rounded-xl bg-gray-200 text-gray-600 cursor-not-allowed">Tidak dapat dibatalkan</button>
        @endif

        <a href="{{ route('events.show', $registration->event->slug) }}"
           class="block text-center w-full px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50">
           Lihat Detail Event
        </a>
        <a href="{{ route('registrations.index') }}"
           class="block text-center w-full px-4 py-2.5 rounded-xl border border-indigo-200 text-indigo-700 hover:bg-indigo-50">
           Kembali ke Pendaftaran Saya
        </a>
      </div>
    </div>

    {{-- Placeholder QR/pass jika nanti approved --}}
    @if($registration->status === 'approved')
      <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <div class="text-sm text-gray-700">Pass / QR Check-in</div>
        <div class="mt-2 text-gray-500 italic">Akan muncul setelah kita aktifkan fitur QR.</div>
      </div>
    @endif
  </aside>
</div>
@endsection
