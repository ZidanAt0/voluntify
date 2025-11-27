@extends('layouts.dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">Riwayat Check-in Peserta</h1>

<div class="bg-white rounded-2xl shadow ring-1 ring-gray-200 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-4 py-2 text-left">Peserta</th>
        <th class="px-4 py-2">Event</th>
        <th class="px-4 py-2">Kode</th>
        <th class="px-4 py-2">Waktu Check-in</th>
        <th class="px-4 py-2">Status</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      @forelse($checkins as $c)
        <tr>
          <td class="px-4 py-2">{{ $c->user->name }}</td>
          <td class="px-4 py-2">{{ $c->event->title }}</td>
          <td class="px-4 py-2 font-mono font-bold">{{ $c->checkin_code }}</td>
          <td class="px-4 py-2">
            {{ $c->updated_at->format('d M Y H:i') }}
          </td>
          <td class="px-4 py-2">
            <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs">
              Checked In
            </span>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center py-6 text-gray-500">
            Belum ada peserta yang check-in.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">
  {{ $checkins->links() }}
</div>
@endsection
