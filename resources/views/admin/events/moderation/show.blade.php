@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-3xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
    <img src="{{ $event->banner_url }}" class="w-full h-64 object-cover">
    <div class="p-6 space-y-3">
        <div class="flex items-center gap-3 flex-wrap">
            <h1 class="text-2xl font-bold">{{ $event->title }}</h1>
            @if($event->review_status === 'pending')
                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">Pending</span>
            @elseif($event->review_status === 'rejected')
                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">Rejected</span>
            @else
                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">Approved</span>
            @endif
            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs">{{ ucfirst($event->status) }}</span>
            @if($event->category)
                <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs">{{ $event->category->name }}</span>
            @endif
        </div>
        <div class="text-gray-600 text-sm">
            {{ $event->date_human }} · {{ $event->time_range }} · {{ $event->location_human }}
        </div>
        <div class="text-sm text-gray-700 whitespace-pre-line">
            {{ $event->excerpt }}
        </div>
        <div class="text-sm text-gray-500">
            Organizer: {{ $event->organizer->name ?? '-' }} ({{ $event->organizer->email ?? '-' }})
        </div>
    </div>
</div>

<div class="mt-6 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6 space-y-3">
    <h2 class="text-lg font-semibold">Aksi Moderasi</h2>
    <div class="flex flex-wrap gap-3">
        <form method="POST" action="{{ route('admin.events.approve', $event) }}">
            @csrf
            <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 text-sm">
                Approve
            </button>
        </form>
        <form method="POST" action="{{ route('admin.events.reject', $event) }}">
            @csrf
            <button class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700 text-sm">
                Reject
            </button>
        </form>
        <form method="POST" action="{{ route('admin.events.close', $event) }}">
            @csrf
            <button class="px-4 py-2 rounded-xl bg-yellow-500 text-white hover:bg-yellow-600 text-sm">
                Close Registration
            </button>
        </form>
        <form method="POST" action="{{ route('admin.events.cancel', $event) }}">
            @csrf
            <button class="px-4 py-2 rounded-xl bg-gray-800 text-white hover:bg-gray-900 text-sm">
                Cancel Event
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg p-3">
            {{ session('error') }}
        </div>
    @endif
</div>
@endsection
