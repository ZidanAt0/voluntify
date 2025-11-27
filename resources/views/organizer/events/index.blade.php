@extends('layouts.dashboard')

@section('content')
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Event Saya</h1>
    <a href="{{ route('organizer.events.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded-xl">+ Buat Event</a>
</div>

@if(session('success'))
<div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg">
    {{ session('success') }}
</div>
@endif

<div class="mt-6 bg-white p-4 rounded-xl shadow ring-1 ring-gray-200">
    <table class="w-full text-sm">
        <thead class="font-medium text-gray-600">
            <tr class="border-b">
                <th class="py-2 text-left">Judul</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($events as $event)
            <tr class="border-b">
                <td class="py-2">{{ $event->title }}</td>
                <td>{{ $event->starts_at }}</td>
                <td>{{ ucfirst($event->status) }}</td>
                <td class="p-3 text-center space-x-2">

    <!-- ✅ EDIT -->
    <a href="{{ route('organizer.events.edit', $event->id) }}"
       class="px-3 py-1 text-sm bg-yellow-400 text-black rounded hover:bg-yellow-500">
        Edit
    </a>

    <!-- ✅ PUBLISH / UNPUBLISH -->
    @if($event->status === 'draft')
        <form method="POST"
              action="{{ route('organizer.events.publish', $event->id) }}"
              class="inline">
            @csrf
            <button class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                Publish
            </button>
        </form>
    @else
        <form method="POST"
              action="{{ route('organizer.events.unpublish', $event->id) }}"
              class="inline">
            @csrf
            <button class="px-3 py-1 text-sm bg-gray-600 text-white rounded hover:bg-gray-700">
                Unpublish
            </button>
        </form>
    @endif

    <!-- ✅ HAPUS -->
    <form method="POST"
          action="{{ route('organizer.events.destroy', $event->id) }}"
          class="inline"
          onsubmit="return confirm('Yakin hapus event ini?')">
        @csrf
        @method('DELETE')
        <button class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
            Hapus
        </button>
    </form>
    <a href="{{ route('organizer.events.participants', $event->id) }}"
   class="px-3 py-1 text-sm bg-blue-500 text-white rounded">
   Peserta
</a>


</td>


            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-4 text-gray-600">Belum ada event.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $events->links() }}
    </div>
</div>
@endsection
