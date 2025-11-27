@extends('layouts.dashboard')

@section('content')
{{-- HERO --}}
<div class="overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-sky-500 text-white">
  <div class="p-6 sm:p-8 flex items-center justify-between">
    <div class="flex items-center gap-4">
      <img src="{{ auth()->user()->avatar_url }}" class="w-14 h-14 rounded-full object-cover ring-4 ring-white/20" alt="">
      <div>
        <div class="text-white/90 text-sm">Selamat datang kembali,</div>
        <h1 class="text-2xl sm:text-3xl font-semibold">{{ auth()->user()->name }}</h1>
        <p class="text-white/80 text-sm mt-1">Ini ringkasan aktivitas volunteer kamu.</p>
      </div>
    </div>
    <div class="hidden sm:flex items-center gap-6">
      <a href="{{ route('events.index') }}" class="px-4 py-2 rounded-xl bg-white text-indigo-700 hover:bg-gray-50">Jelajahi Event</a>
    </div>
  </div>
</div>

{{-- KPI CARDS --}}
<div class="mt-6 grid gap-4 sm:grid-cols-3">
  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
    <div class="text-sm text-gray-600">Total Pendaftaran</div>
    <div class="mt-1 text-3xl font-semibold">{{ $totalRegs }}</div>
  </div>
  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
    <div class="text-sm text-gray-600">Disetujui</div>
    <div class="mt-1 text-3xl font-semibold text-green-600">{{ $approvedCount }}</div>
  </div>
  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
    <div class="text-sm text-gray-600">Menunggu Review</div>
    <div class="mt-1 text-3xl font-semibold text-yellow-600">{{ $appliedCount }}</div>
  </div>
</div>

<div class="mt-6 grid lg:grid-cols-3 gap-6">
  {{-- COL 1-2 --}}
  <div class="lg:col-span-2 space-y-6">

    {{-- UPCOMING --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Event Terdekat</h2>
        <a href="{{ route('registrations.index') }}" class="text-sm text-indigo-700 hover:underline">Lihat semua</a>
      </div>

      @if($upcomingRegs->isEmpty())
        <p class="text-gray-600 mt-3">Belum ada event mendatang. Coba jelajahi <a class="text-indigo-700 hover:underline" href="{{ route('events.index') }}">daftar event</a>.</p>
      @else
        <div class="mt-4 divide-y">
          @foreach($upcomingRegs as $r)
            @php $e = $r->event; @endphp
            <div class="py-3 flex items-start gap-4">
              <img src="{{ $e->banner_url }}" class="w-20 h-16 object-cover rounded-lg ring-1 ring-gray-200" alt="">
              <div class="flex-1">
                <div class="flex items-center gap-2">
                  <a href="{{ route('events.show', $e->slug) }}" class="font-medium hover:underline">{{ $e->title }}</a>
                  @if($e->category)
                    <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200">{{ $e->category->name }}</span>
                  @endif
                </div>
                <div class="text-sm text-gray-600">
                  {{ $e->date_human }} · {{ $e->time_range }} · {{ $e->location_human }}
                </div>
              </div>
              <a href="{{ route('registrations.show', $r) }}" class="text-sm px-3 py-1.5 rounded-lg border border-indigo-200 text-indigo-700 hover:bg-indigo-50">Detail</a>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- RECENT ACTIVITY --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Aktivitas Terbaru</h2>
        <a href="{{ route('registrations.index') }}" class="text-sm text-indigo-700 hover:underline">Lihat semua</a>
      </div>

      @if($recentRegs->isEmpty())
        <p class="text-gray-600 mt-3">Belum ada aktivitas.</p>
      @else
        <ul class="mt-3 space-y-2">
          @foreach($recentRegs as $r)
            @php
              $e = $r->event;
              $badge = [
                'applied'=>'bg-yellow-50 text-yellow-700 ring-yellow-200',
                'approved'=>'bg-green-50 text-green-700 ring-green-200',
                'waitlisted'=>'bg-blue-50 text-blue-700 ring-blue-200',
                'rejected'=>'bg-red-50 text-red-700 ring-red-200',
                'checked_in'=>'bg-indigo-50 text-indigo-700 ring-indigo-200',
                'completed'=>'bg-emerald-50 text-emerald-700 ring-emerald-200',
                'cancelled'=>'bg-gray-100 text-gray-700 ring-gray-300',
              ][$r->status] ?? 'bg-gray-100 text-gray-700 ring-gray-300';
            @endphp
            <li class="p-3 rounded-xl ring-1 ring-gray-200 flex items-center justify-between">
              <div class="min-w-0">
                <div class="truncate"><span class="font-medium">{{ $e->title }}</span></div>
                <div class="text-sm text-gray-600">{{ $e->date_human }} · {{ $e->location_human }}</div>
              </div>
              <span class="ml-4 inline-flex px-2.5 py-0.5 rounded-full text-xs ring-1 {{ $badge }}">
                {{ ucfirst(str_replace('_',' ',$r->status)) }}
              </span>
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>

  {{-- COL 3 --}}
  <aside class="space-y-6">
    {{-- BOOKMARKS --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Bookmark Terbaru</h2>
        <a href="{{ route('bookmarks.index') }}" class="text-sm text-indigo-700 hover:underline">Lihat semua</a>
      </div>

      @if($bookmarkedEvents->isEmpty())
        <p class="text-gray-600 mt-3">Belum ada bookmark.</p>
      @else
        <div class="mt-4 grid grid-cols-1 gap-3">
          @foreach($bookmarkedEvents as $e)
            <a href="{{ route('events.show', $e->slug) }}" class="flex items-center gap-3 p-2 rounded-xl ring-1 ring-gray-200 hover:bg-gray-50">
              <img src="{{ $e->banner_url }}" class="w-16 h-12 object-cover rounded-md ring-1 ring-gray-200" alt="">
              <div class="min-w-0">
                <div class="text-sm font-medium truncate">{{ $e->title }}</div>
                <div class="text-xs text-gray-600 truncate">{{ $e->date_human }} · {{ $e->location_human }}</div>
              </div>
            </a>
          @endforeach
        </div>
      @endif
    </div>

    {{-- QUICK LINKS --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
      <h2 class="text-lg font-semibold">Tautan Cepat</h2>
      <div class="mt-3 grid gap-2">
        <a href="{{ route('events.index') }}" class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Cari Event</a>
        <a href="{{ route('registrations.index') }}" class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Pendaftaran Saya</a>
        <a href="{{ route('bookmarks.index') }}" class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Bookmark</a>
        <a href="{{ route('profile.edit') }}" class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Edit Profil</a>
      </div>
    </div>
  </aside>
</div>
@endsection
