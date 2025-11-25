@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <h1 class="text-2xl font-bold">Explore Events</h1>

    {{-- Filter Bar (tetap) --}}
    <form method="GET" class="mt-4 grid gap-3 sm:grid-cols-5 bg-white p-4 rounded-2xl shadow-sm ring-1 ring-gray-200">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul…"
               class="sm:col-span-2 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

        <select name="category" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->slug }}" @selected(request('category')===$cat->slug)>{{ $cat->name }}</option>
            @endforeach
        </select>

        <input type="text" name="city" value="{{ request('city') }}" placeholder="Kota"
               class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

        <select name="status" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Status</option>
            <option value="open" @selected(request('status')==='open')>Open</option>
            <option value="closed" @selected(request('status')==='closed')>Closed</option>
        </select>

        <div class="sm:col-span-5 grid grid-cols-2 gap-3">
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="sm:col-span-5">
            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">Filter</button>
            <a href="{{ route('events.index') }}" class="ml-2 text-sm text-gray-600 hover:underline">Reset</a>
        </div>
    </form>

    {{-- Grid Cards ala contoh --}}
    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($events as $e)
            <div class="bg-white rounded-2xl shadow-md ring-1 ring-gray-200 overflow-hidden flex flex-col">
                <a href="{{ route('events.show', $e->slug) }}">
                    <img src="{{ $e->banner_url }}" alt="{{ $e->title }}" class="w-full h-40 object-cover">
                </a>

                <div class="p-4 flex-1">
                    <h3 class="font-semibold text-lg">{{ $e->title }}</h3>
                    
                    <p class="text-gray-600 mt-1"
                       style="-webkit-line-clamp:2;display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;">
                        {{ $e->excerpt ?? Str::limit(strip_tags($e->description), 100) }}
                    </p>

                    @if($e->category)
                    <div class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200">
                        {{ $e->category->name }}
                        </span>
                    </div>
                    @endif

                    <ul class="mt-4 space-y-2 text-sm text-gray-700">
                        <li class="flex items-center gap-2">
                            {{-- calendar icon --}}
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <path d="M7 2v4M17 2v4M3 10h18M5 6h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            {{ $e->date_human }}
                        </li>
                        <li class="flex items-center gap-2">
                            {{-- clock icon --}}
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            {{ $e->time_range }}
                        </li>
                        <li class="flex items-center gap-2">
                            {{-- map-pin icon --}}
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <path d="M12 21s7-4.35 7-10a7 7 0 1 0-14 0c0 5.65 7 10 7 10z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="11" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            {{ $e->location_human }}
                        </li>
                        <li class="flex items-center gap-2">
                            {{-- users icon --}}
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <path d="M16 14a4 4 0 1 1 6 3.465M2 17.465A4 4 0 1 1 8 14M12 13a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            {{ $e->organizer?->name ?? 'Organizer' }}
                        </li>
                    </ul>

                    {{-- Status pill --}}
                    <div class="mt-3">
                        @if($e->status === 'cancelled')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-gray-700 bg-gray-100 ring-1 ring-gray-300">Dibatalkan</span>
                        @elseif($e->is_closed)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-red-700 bg-red-50 ring-1 ring-red-200">Pendaftaran Ditutup</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-green-700 bg-green-50 ring-1 ring-green-200">Open</span>
                        @endif
                    </div>
                </div>

                <div class="px-4 pb-4 flex items-center gap-2">
                    <a href="{{ route('events.show', $e->slug) }}" class="flex-1 text-center rounded-xl border border-indigo-200 text-indigo-700 hover:bg-indigo-50 px-4 py-2">
                        Lihat Detail Event
                    </a>
                    @auth
                        @php $isBookmarked = auth()->user()->bookmarks->where('event_id',$e->id)->isNotEmpty(); @endphp
                        @if($isBookmarked)
                        <form method="POST" action="{{ route('bookmarks.destroy', $e) }}">
                            @csrf @method('DELETE')
                            <button title="Hapus Bookmark" class="px-3 py-2 rounded-xl border hover:bg-gray-50">★</button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('bookmarks.store', $e) }}">
                            @csrf
                            <button title="Simpan Event" class="px-3 py-2 rounded-xl border hover:bg-gray-50">☆</button>
                        </form>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-3 text-center text-gray-600">
                Tidak ada event yang cocok dengan filter.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $events->links() }}
    </div>
</div>
@endsection
