@extends('layouts.dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-0 py-0">
    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('admin.events.moderation.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                <path d="M19 12H5M5 12l7 7M5 12l7-7" stroke="currentColor" stroke-width="2"/>
            </svg>
            Kembali ke Moderasi
        </a>
    </div>

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

    {{-- Event Card --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <img src="{{ $event->banner_url }}" class="w-full h-64 object-cover">

        <div class="p-6 space-y-4">
            {{-- Title & Badges --}}
            <div>
                <h1 class="text-3xl font-bold mb-3">{{ $event->title }}</h1>
                <div class="flex flex-wrap gap-2">
                    {{-- Review Status Badge --}}
                    @if($event->review_status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700 ring-1 ring-yellow-200">
                            ⏳ Pending Review
                        </span>
                    @elseif($event->review_status === 'rejected')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700 ring-1 ring-red-200">
                            ❌ Rejected
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700 ring-1 ring-green-200">
                            ✓ Approved
                        </span>
                    @endif

                    {{-- Event Status Badge --}}
                    @if($event->status === 'draft')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700 ring-1 ring-gray-300">
                            Draft
                        </span>
                    @elseif($event->status === 'published')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-700 ring-1 ring-blue-200">
                            Published
                        </span>
                    @elseif($event->status === 'closed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-orange-100 text-orange-700 ring-1 ring-orange-200">
                            Closed
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700 ring-1 ring-gray-300">
                            Cancelled
                        </span>
                    @endif

                    @if($event->category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200">
                            {{ $event->category->name }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Event Details --}}
            <div class="grid md:grid-cols-2 gap-4 py-4 border-y border-gray-200">
                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <path d="M7 2v4M17 2v4M3 10h18M5 6h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal</p>
                        <p class="font-medium">{{ $event->date_human }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Waktu</p>
                        <p class="font-medium">{{ $event->time_range }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <path d="M12 21s7-4.35 7-10a7 7 0 1 0-14 0c0 5.65 7 10 7 10z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="11" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Lokasi</p>
                        <p class="font-medium">{{ $event->location_human }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <path d="M16 14a4 4 0 1 1 6 3.465M2 17.465A4 4 0 1 1 8 14M12 13a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Organizer</p>
                        <p class="font-medium">{{ $event->organizer->name ?? '-' }}</p>
                        <p class="text-sm text-gray-500">{{ $event->organizer->email ?? '-' }}</p>
                    </div>
                </div>

                @if($event->capacity)
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.5"/>
                                <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Kapasitas</p>
                            <p class="font-medium">{{ $event->registration_count }} / {{ $event->capacity }} peserta</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            <div>
                <h2 class="text-lg font-semibold mb-2">Deskripsi Event</h2>
                <div class="text-gray-700 whitespace-pre-line">{{ $event->description ?? $event->excerpt }}</div>
            </div>
        </div>
    </div>

    {{-- Moderation Actions --}}
    <div class="mt-6 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Aksi Moderasi</h2>

        {{-- Review Actions (Approve/Reject) --}}
        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-700 mb-2">Status Review:</h3>
            <div class="grid grid-cols-2 gap-3">
                @if($event->review_status !== 'approved')
                    <form method="POST" action="{{ route('admin.events.approve', $event) }}">
                        @csrf
                        <button class="w-full px-4 py-3 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 font-medium text-sm transition-colors">
                            ✓ Approve
                        </button>
                    </form>
                @else
                    <div class="px-4 py-3 rounded-xl bg-green-100 text-green-700 font-medium text-sm text-center">
                        ✓ Sudah Approved
                    </div>
                @endif

                @if($event->review_status !== 'rejected')
                    <form method="POST" action="{{ route('admin.events.reject', $event) }}">
                        @csrf
                        <button class="w-full px-4 py-3 rounded-xl bg-red-600 text-white hover:bg-red-700 font-medium text-sm transition-colors">
                            ✕ Reject
                        </button>
                    </form>
                @else
                    <div class="px-4 py-3 rounded-xl bg-red-100 text-red-700 font-medium text-sm text-center">
                        ✕ Sudah Rejected
                    </div>
                @endif
            </div>
        </div>

        {{-- Event Status Actions --}}
        <div>
            <h3 class="text-sm font-medium text-gray-700 mb-2">Status Event:</h3>
            <div class="grid grid-cols-2 gap-3">
                {{-- Close/Open Registration Button --}}
                @if($event->status === 'closed')
                    {{-- Show Open Registration button when closed --}}
                    <form method="POST" action="{{ route('admin.events.open', $event) }}">
                        @csrf
                        <button class="w-full px-4 py-3 rounded-xl bg-green-600 text-white hover:bg-green-700 font-medium text-sm transition-colors">
                            Open Registration
                        </button>
                    </form>
                @elseif($event->review_status === 'rejected')
                    {{-- Disable Close button when rejected --}}
                    <div class="px-4 py-3 rounded-xl bg-gray-100 text-gray-400 font-medium text-sm text-center cursor-not-allowed">
                        Close Registration
                    </div>
                @else
                    {{-- Show Close Registration button --}}
                    <form method="POST" action="{{ route('admin.events.close', $event) }}">
                        @csrf
                        <button class="w-full px-4 py-3 rounded-xl bg-amber-500 text-white hover:bg-amber-600 font-medium text-sm transition-colors">
                            Close Registration
                        </button>
                    </form>
                @endif

                {{-- Cancel Event Button --}}
                @if($event->status !== 'cancelled')
                    <form method="POST" action="{{ route('admin.events.cancel', $event) }}">
                        @csrf
                        <button class="w-full px-4 py-3 rounded-xl bg-gray-700 text-white hover:bg-gray-800 font-medium text-sm transition-colors">
                            Cancel Event
                        </button>
                    </form>
                @else
                    <div class="px-4 py-3 rounded-xl bg-gray-100 text-gray-700 font-medium text-sm text-center">
                        Event Dibatalkan
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-4 p-4 bg-gray-50 rounded-xl text-sm text-gray-600">
            <p><strong>Catatan:</strong></p>
            <ul class="mt-2 space-y-1 list-disc list-inside">
                <li><strong>Approve:</strong> Event akan dipublish dan muncul di halaman publik</li>
                <li><strong>Reject:</strong> Event dikembalikan ke draft dan tidak akan muncul di publik. Tombol "Close Registration" otomatis disabled untuk event yang di-reject.</li>
                <li><strong>Close Registration:</strong> Menutup pendaftaran event (event tetap terlihat tapi tidak bisa daftar)</li>
                <li><strong>Open Registration:</strong> Membuka kembali pendaftaran event yang sudah ditutup</li>
                <li><strong>Cancel Event:</strong> Membatalkan event sepenuhnya</li>
            </ul>
        </div>
    </div>
</div>
@endsection
