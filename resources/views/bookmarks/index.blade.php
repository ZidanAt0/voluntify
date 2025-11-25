@extends('layouts.dashboard')

@section('content')
<h1 class="text-2xl font-bold">Event Tersimpan</h1>

@if (session('status'))
  <div class="mt-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
    {{ session('status') }}
  </div>
@endif

<div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
  @forelse ($events as $e)
    <div class="bg-white rounded-2xl shadow-md ring-1 ring-gray-200 overflow-hidden">
      <a href="{{ route('events.show', $e->slug) }}"><img src="{{ $e->banner_url }}" class="w-full h-40 object-cover"></a>
      <div class="p-4">
        <div class="flex items-center justify-between">
          <h3 class="font-semibold">{{ $e->title }}</h3>
          @if($e->category)
            <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200">{{ $e->category->name }}</span>
          @endif
        </div>
        <div class="text-sm text-gray-600 mt-1">{{ $e->date_human }} · {{ $e->location_human }}</div>
        <div class="mt-3 flex gap-2">
          <a href="{{ route('events.show', $e->slug) }}" class="flex-1 text-center rounded-xl border border-indigo-200 text-indigo-700 hover:bg-indigo-50 px-3 py-2">Detail</a>
          <form method="POST" action="{{ route('bookmarks.destroy', $e) }}">
            @csrf @method('DELETE')
            <button class="px-3 py-2 rounded-xl border hover:bg-gray-50">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  @empty
    <div class="sm:col-span-2 lg:col-span-3 text-gray-600">Belum ada event tersimpan.</div>
  @endforelse
</div>

<div class="mt-6">{{ $events->links() }}</div>
@endsection
