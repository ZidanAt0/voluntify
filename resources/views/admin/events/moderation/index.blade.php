@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Moderasi Event</h1>

@if(session('success'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg p-3">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Judul</th>
                <th class="p-3 text-left">Organizer</th>
                <th class="p-3 text-left">Kategori</th>
                <th class="p-3 text-left">Status Review</th>
                <th class="p-3 text-left">Tanggal</th>
                <th class="p-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($events as $event)
                <tr>
                    <td class="p-3 font-semibold">{{ $event->title }}</td>
                    <td class="p-3 text-gray-600">{{ $event->organizer->name ?? '-' }}</td>
                    <td class="p-3 text-gray-600">{{ $event->category->name ?? '-' }}</td>
                    <td class="p-3">
                        @if($event->review_status === 'pending')
                            <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">Pending</span>
                        @elseif($event->review_status === 'rejected')
                            <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs">Rejected</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs">Approved</span>
                        @endif
                    </td>
                    <td class="p-3 text-gray-600">{{ $event->date_human }}</td>
                    <td class="p-3 text-right">
                        <a href="{{ route('admin.events.moderation.show', $event) }}"
                           class="px-3 py-1 rounded bg-white border border-gray-200 hover:bg-gray-50 text-xs">
                            Review
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">Tidak ada event pending/rejected.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $events->links() }}
</div>
@endsection
