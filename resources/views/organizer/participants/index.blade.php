@extends('layouts.dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">
    Peserta Event: {{ $event->title }}
</h1>

<div class="bg-white rounded-xl shadow ring-1 ring-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
        <tr>
            <th class="p-3 text-left">Nama</th>
            <th class="p-3 text-left">Email</th>
            <th class="p-3 text-center">Status</th>
            <th class="p-3 text-center">Aksi</th>
        </tr>
        </thead>

        <tbody class="divide-y">
        @forelse($registrations as $r)
            <tr>
                <td class="p-3">{{ $r->user->name }}</td>
                <td class="p-3">{{ $r->user->email }}</td>

                <td class="p-3 text-center">
                    <span class="px-3 py-1 rounded-full text-xs
                        @if($r->status=='applied') bg-yellow-100 text-yellow-700
                        @elseif($r->status=='approved') bg-green-100 text-green-700
                        @elseif($r->status=='rejected') bg-red-100 text-red-700
                        @endif">
                        {{ ucfirst($r->status) }}
                    </span>
                </td>

                <td class="p-3 text-center flex gap-2 justify-center">

                    @if($r->status == 'applied')
                        <form method="POST" action="{{ route('organizer.registrations.approve', $r->id) }}">
                            @csrf
                            <button class="px-3 py-1 bg-green-600 text-white rounded text-xs">
                                Approve
                            </button>
                        </form>

                        <form method="POST" action="{{ route('organizer.registrations.reject', $r->id) }}">
                            @csrf
                            <button class="px-3 py-1 bg-red-600 text-white rounded text-xs">
                                Reject
                            </button>
                        </form>
                    @else
                        <span class="text-gray-400 text-xs">Selesai</span>
                    @endif

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="p-4 text-center text-gray-500">
                    Belum ada pendaftar.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    <a href="{{ route('organizer.events.index') }}"
       class="px-5 py-2 border rounded-xl">
        ← Kembali ke Event
    </a>
</div>
@endsection
