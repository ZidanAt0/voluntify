@extends('layouts.dashboard')

@section('content')
{{-- HERO --}}
<div class="overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-sky-500 text-white">
  <div class="p-6 sm:p-8">
    <div class="text-white/90 text-sm">Detail Pendaftaran</div>
    <h1 class="text-2xl sm:text-3xl font-semibold">
      {{ $registration->event->title }}
    </h1>
    <p class="text-white/80 text-sm mt-1">
      {{ $registration->event->date_human }}
      · {{ $registration->event->time_range }}
      · {{ $registration->event->location_human }}
    </p>
  </div>
</div>

{{-- FLASH MESSAGE --}}
@if (session('status'))
  <div class="mt-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
    {{ session('status') }}
  </div>
@endif

<div class="mt-6 grid lg:grid-cols-3 gap-6">

  {{-- ================= LEFT CONTENT ================= --}}
  <div class="lg:col-span-2 space-y-6">

    {{-- EVENT SUMMARY --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
      <img src="{{ $registration->event->banner_url }}" class="w-full h-48 object-cover" alt="Banner">
      <div class="p-6">
        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-700">

          @if($registration->event->category)
            <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200 text-xs">
              {{ $registration->event->category->name }}
            </span>
          @endif

          <span class="flex items-center gap-1">
            📅 {{ $registration->event->date_human }} · {{ $registration->event->time_range }}
          </span>

          <span class="flex items-center gap-1">
            📍 {{ $registration->event->location_human }}
          </span>

        </div>
      </div>
    </div>

    {{-- ================= JAWABAN FORMULIR ================= --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
      <h2 class="text-lg font-semibold mb-4">Jawaban Formulir</h2>

      @php
        $a = $registration->answers ?? [];
        $labels = [
          'whatsapp' => 'WhatsApp',
          'city' => 'Kota / Domisili',
          'motivation' => 'Motivasi',
          'experience' => 'Pengalaman',
          'skills' => 'Keahlian',
          'emergency_name' => 'Kontak Darurat (Nama)',
          'emergency_phone'=> 'Kontak Darurat (Nomor)',
        ];
      @endphp

      <div class="grid gap-4">
        @foreach ($labels as $key => $label)
          @continue(!array_key_exists($key, $a))

          <div class="rounded-xl ring-1 ring-gray-200 p-4">
            <div class="text-xs text-gray-500">{{ $label }}</div>

            @if($key === 'skills' && is_array($a[$key]))
              <div class="mt-2 flex flex-wrap gap-2">
                @foreach ($a[$key] as $skill)
                  <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 ring-1 ring-gray-200">
                    {{ $skill }}
                  </span>
                @endforeach
              </div>
            @else
              <div class="mt-1 text-gray-800 whitespace-pre-line">
                {{ $a[$key] ?: '-' }}
              </div>
            @endif
          </div>
        @endforeach

        @if(empty($a))
          <p class="text-gray-600">Belum ada jawaban tersimpan.</p>
        @endif
      </div>
    </div>

  </div>

  {{-- ================= RIGHT SIDEBAR ================= --}}
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
      <div class="mt-1 inline-flex px-3 py-1 rounded-full ring-1 {{ $badge }}">
        {{ ucfirst(str_replace('_',' ',$registration->status)) }}
      </div>

      <dl class="mt-4 text-sm text-gray-700 space-y-1">
        <div class="flex justify-between">
          <dt>Didaftarkan</dt>
          <dd>{{ $registration->applied_at?->format('d M Y H:i') }}</dd>
        </div>

        @if($registration->cancelled_at)
        <div class="flex justify-between">
          <dt>Dibatalkan</dt>
          <dd>{{ $registration->cancelled_at->format('d M Y H:i') }}</dd>
        </div>
        @endif
      </dl>

      <div class="mt-4 space-y-2">

        @if($registration->cancellable())
        <form method="POST" action="{{ route('registrations.cancel', $registration) }}"
              onsubmit="return confirm('Batalkan pendaftaran?');">
          @csrf @method('DELETE')
          <button class="w-full px-4 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-700">
            Batalkan Pendaftaran
          </button>
        </form>
        @else
          <button disabled class="w-full px-4 py-2.5 rounded-xl bg-gray-200 text-gray-600">
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

      </div>
    </div>

    {{-- ================= KODE CHECK-IN ================= --}}
    @if($registration->status === 'approved' && $registration->checkin_code)
    <div class="p-4 bg-indigo-50 rounded-xl">
      <p class="text-sm text-gray-600">Kode Check-in Kamu:</p>
      <p class="text-2xl font-bold tracking-widest text-indigo-700 text-center">
        {{ $registration->checkin_code }}
      </p>
    </div>
    @endif

  </aside>
</div>
@endsection