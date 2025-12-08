@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-0 py-0">
    <h1 class="text-2xl font-bold">Moderasi Event</h1>

    @if(session('success'))
        <div class="mt-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mt-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg p-3">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter Bar --}}
    <form method="GET" class="mt-4 bg-white p-4 rounded-2xl shadow-sm ring-1 ring-gray-200 space-y-3">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau organizer..."
                   class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

            <select name="review_status" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status Review</option>
                <option value="pending" @selected(request('review_status')==='pending')>Pending</option>
                <option value="approved" @selected(request('review_status')==='approved')>Approved</option>
                <option value="rejected" @selected(request('review_status')==='rejected')>Rejected</option>
            </select>

            <select name="status" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status Event</option>
                <option value="draft" @selected(request('status')==='draft')>Draft</option>
                <option value="published" @selected(request('status')==='published')>Published</option>
                <option value="closed" @selected(request('status')==='closed')>Closed</option>
                <option value="cancelled" @selected(request('status')==='cancelled')>Cancelled</option>
            </select>

            <select name="category_id" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category_id')==$cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 text-sm">
                Filter
            </button>
            <a href="{{ route('admin.events.moderation.index') }}"
               class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">
                Reset
            </a>
        </div>
    </form>

    {{-- Event Cards Grid --}}
    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($events as $event)
            <div class="bg-white rounded-2xl shadow-md ring-1 ring-gray-200 overflow-hidden flex flex-col">
                <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="w-full h-40 object-cover">

                <div class="p-4 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold text-lg flex-1">{{ $event->title }}</h3>
                    </div>

                    <div class="mt-2 flex flex-wrap gap-1.5">
                        {{-- Review Status Badge --}}
                        @if($event->review_status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 ring-1 ring-yellow-200">
                                Pending
                            </span>
                        @elseif($event->review_status === 'rejected')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-red-100 text-red-700 ring-1 ring-red-200">
                                Rejected
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-green-100 text-green-700 ring-1 ring-green-200">
                                Approved
                            </span>
                        @endif

                        {{-- Event Status Badge --}}
                        @if($event->status === 'draft')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-gray-100 text-gray-700 ring-1 ring-gray-300">
                                Draft
                            </span>
                        @elseif($event->status === 'published')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700 ring-1 ring-blue-200">
                                Published
                            </span>
                        @elseif($event->status === 'closed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-orange-100 text-orange-700 ring-1 ring-orange-200">
                                Closed
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-gray-100 text-gray-700 ring-1 ring-gray-300">
                                Cancelled
                            </span>
                        @endif

                        {{-- Category Badge --}}
                        @if($event->category)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200">
                                {{ $event->category->name }}
                            </span>
                        @endif
                    </div>

                    <ul class="mt-4 space-y-2 text-sm text-gray-700">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <path d="M7 2v4M17 2v4M3 10h18M5 6h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            {{ $event->date_human }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            {{ $event->time_range }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <path d="M12 21s7-4.35 7-10a7 7 0 1 0-14 0c0 5.65 7 10 7 10z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="11" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            {{ $event->location_human }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <path d="M16 14a4 4 0 1 1 6 3.465M2 17.465A4 4 0 1 1 8 14M12 13a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            {{ $event->organizer?->name ?? 'Organizer' }}
                        </li>
                        @if($event->capacity)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.5"/>
                                    <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                                {{ $event->registration_count }} / {{ $event->capacity }} peserta
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="px-4 pb-4">
                    <a href="{{ route('admin.events.moderation.show', $event) }}"
                       class="block w-full text-center rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2">
                        Review Event
                    </a>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-3 text-center py-8 text-gray-600">
                Tidak ada event yang cocok dengan filter.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $events->links() }}
    </div>
</div>
@endsection
