@extends('layouts.dashboard')

@section('content')
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Event Saya</h1>
    <a href="{{ route('organizer.events.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">+ Buat Event</a>
</div>

@if(session('success'))
<div class="mt-4 p-3 bg-green-50 text-green-700 rounded-lg border border-green-200">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg border border-red-200">
    {{ session('error') }}
</div>
@endif

<div class="mt-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Event</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Review</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
            @forelse($events as $event)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $event->title }}</div>
                    <div class="text-xs text-gray-500">{{ $event->category?->name }}</div>
                </td>
                <td class="px-6 py-4 text-gray-700">
                    {{ $event->starts_at->locale('id')->format('d M Y, H:i') }}
                </td>
                <td class="px-6 py-4">
                    @if($event->status === 'draft')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Draft
                        </span>
                    @elseif($event->status === 'published')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Published
                        </span>
                    @elseif($event->status === 'closed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            Closed
                        </span>
                    @elseif($event->status === 'cancelled')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Cancelled
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ ucfirst($event->status) }}
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($event->review_status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Menunggu Review
                        </span>
                    @elseif($event->review_status === 'approved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Disetujui
                        </span>
                    @elseif($event->review_status === 'rejected')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Belum Diajukan
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        {{-- Edit Button --}}
                        <a href="{{ route('organizer.events.edit', $event->id) }}"
                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-yellow-50 text-yellow-700 border border-yellow-200 hover:bg-yellow-100 transition-colors"
                           title="Edit Event">
                            Edit
                        </a>

                        {{-- Submit untuk Review / Unpublish --}}
                        @if($event->status === 'draft' && is_null($event->review_status))
                            {{-- Draft yang belum pernah diajukan --}}
                            <form method="POST" action="{{ route('organizer.events.publish', $event->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 transition-colors"
                                        title="Submit untuk Review Admin">
                                    Submit untuk Review
                                </button>
                            </form>
                        @elseif($event->status === 'draft' && $event->review_status === 'rejected')
                            {{-- Draft yang ditolak - bisa submit ulang --}}
                            <form method="POST" action="{{ route('organizer.events.publish', $event->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-orange-50 text-orange-700 border border-orange-200 hover:bg-orange-100 transition-colors"
                                        title="Submit Ulang untuk Review">
                                    Submit Ulang
                                </button>
                            </form>
                        @elseif($event->review_status === 'approved' && $event->status === 'published')
                            {{-- Event yang sudah approved dan published - bisa unpublish --}}
                            <form method="POST" action="{{ route('organizer.events.unpublish', $event->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100 transition-colors"
                                        title="Unpublish Event">
                                    Unpublish
                                </button>
                            </form>
                        @endif

                        {{-- Participants Link --}}
                        @if($event->status === 'published' || $event->status === 'closed')
                            <a href="{{ route('organizer.events.participants', $event->id) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition-colors"
                               title="Lihat Peserta">
                                Peserta
                            </a>
                        @endif

                        {{-- Delete Button --}}
                        <form method="POST"
                              action="{{ route('organizer.events.destroy', $event->id) }}"
                              class="inline"
                              onsubmit="return confirm('Yakin hapus event {{ $event->title }}? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition-colors"
                                    title="Hapus Event">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="mt-2">Belum ada event.</p>
                    <a href="{{ route('organizer.events.create') }}" class="mt-3 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                        + Buat Event Pertama
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        {{ $events->links() }}
    </div>
</div>
@endsection
