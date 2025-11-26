@extends('layouts.dashboard')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Event Saya</h1>
    <a href="{{ route('organizer.events.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
       + Buat Event
    </a>
</div>

@if(session('success'))
    <div class="mb-4 text-green-700 bg-green-100 p-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow ring-1 ring-gray-200">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Judul</th>
                <th class="p-3">Tanggal</th>
                <th class="p-3">Status</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr class="border-t">
                    <td class="p-3">{{ $event->title }}</td>
                    <td class="p-3 text-center">
                        {{ $event->starts_at }}
                    </td>
                    <td class="p-3 text-center">
                        <span class="px-2 py-1 rounded bg-gray-100">
                            {{ $event->status }}
                        </span>
                    </td>
                    <td class="p-3 text-center flex gap-2 justify-center">
                        <a href="{{ route('organizer.events.edit', $event->id) }}"
                           class="px-3 py-1 text-sm bg-yellow-400 rounded">
                           Edit
                        </a>

                        <form method="POST" action="{{ route('organizer.events.destroy', $event->id) }}">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus event ini?')"
                                class="px-3 py-1 text-sm bg-red-600 text-white rounded">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">Belum ada event.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $events->links() }}
</div>
@endsection
