@extends('layouts.dashboard')

@section('content')
{{-- HERO --}}
<div class="overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-sky-500 text-white">
  <div class="p-6 sm:p-8 flex items-center justify-between">
    <div>
      <h1 class="text-2xl sm:text-3xl font-semibold">Event Tersimpan</h1>
      <p class="text-white/80 text-sm mt-1">Kumpulkan event favoritmu untuk ditindaklanjuti nanti.</p>
    </div>
    <div class="hidden sm:block text-right">
      <div class="text-3xl font-bold">{{ $events->total() }}</div>
      <div class="text-white/80 text-sm">Total Bookmark</div>
    </div>
  </div>
</div>

@if (session('status'))
  <div class="mt-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
    {{ session('status') }}
  </div>
@endif

{{-- FILTER BAR --}}
<form method="GET" class="mt-6 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-4 grid gap-3 sm:grid-cols-5">
  <input type="text" name="q" value="{{ $q }}" placeholder="Cari judul event…"
         class="sm:col-span-2 rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

  <select name="category_id" class="rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
    <option value="">Semua Kategori</option>
    @foreach($categories as $c)
      <option value="{{ $c->id }}" @selected($categoryId==$c->id)>{{ $c->name }}</option>
    @endforeach
  </select>

  <select name="sort" class="rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
    <option value="new"  @selected($sort==='new')>Terbaru disimpan</option>
    <option value="soon" @selected($sort==='soon')>Event terdekat</option>
  </select>

  <div class="sm:col-span-1 flex gap-2">
    <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">Terapkan</button>
    <a href="{{ route('bookmarks.index') }}" class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Reset</a>
  </div>
</form>

{{-- GRID --}}
<div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
  @forelse($events as $e)
    <div class="bg-white rounded-2xl shadow-md ring-1 ring-gray-200 overflow-hidden flex flex-col">
      <a href="{{ route('events.show', $e->slug) }}">
        <img src="{{ $e->banner_url }}" alt="" class="w-full h-40 object-cover" loading="lazy">
      </a>

      <div class="p-4 flex-1">
        <div class="flex items-start justify-between gap-3">
          <h3 class="font-semibold leading-tight">
            <a class="hover:underline" href="{{ route('events.show', $e->slug) }}">{{ $e->title }}</a>
          </h3>
          @if($e->category)
            <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200">
              {{ $e->category->name }}
            </span>
          @endif
        </div>

        <ul class="mt-3 space-y-1.5 text-sm text-gray-700">
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M7 2v4M17 2v4M3 10h18M5 6h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="1.5"/></svg>
            {{ $e->date_human }} · {{ $e->time_range }}
          </li>
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 21s7-4.35 7-10a7 7 0 1 0-14 0c0 5.65 7 10 7 10z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="11" r="2.5" stroke="currentColor" stroke-width="1.5"/></svg>
            {{ $e->location_human }}
          </li>
        </ul>
      </div>

      <div class="px-4 pb-4 flex items-center gap-2">
        <a href="{{ route('events.show', $e->slug) }}"
           class="flex-1 text-center rounded-xl border border-indigo-200 text-indigo-700 hover:bg-indigo-50 px-3 py-2">
           Lihat Detail
        </a>

        {{-- tombol hapus bookmark --}}
        <form method="POST" action="{{ route('bookmarks.destroy', $e) }}">
          @csrf @method('DELETE')
          <button class="px-3 py-2 rounded-xl border hover:bg-gray-50" title="Hapus Bookmark">★</button>
        </form>
      </div>
    </div>
  @empty
    <div class="sm:col-span-2 lg:col-span-3">
      <div class="rounded-2xl bg-white ring-1 ring-gray-200 p-10 text-center">
        <div class="text-lg font-semibold text-gray-800">Belum ada event tersimpan</div>
        <p class="text-gray-600 mt-1">Temukan event yang menarik lalu klik “Simpan Event”.</p>
        <a href="{{ route('events.index') }}" class="inline-block mt-4 px-5 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
          Jelajahi Event
        </a>
      </div>
    </div>
  @endforelse
</div>

<div class="mt-6">{{ $events->links() }}</div>
@endsection
