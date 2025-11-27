@extends('layouts.dashboard')

@section('content')
{{-- Hero --}}
<div class="overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-sky-500 text-white">
  <div class="p-6 sm:p-8 flex items-center justify-between">
    <div>
      <h1 class="text-2xl sm:text-3xl font-semibold">Pendaftaran Saya</h1>
      <p class="text-white/80 text-sm mt-1">Kelola semua event yang kamu ikuti.</p>
    </div>
    <div class="hidden sm:block text-right">
      <div class="text-3xl font-bold">{{ $regs->total() }}</div>
      <div class="text-white/80 text-sm">Total Pendaftaran</div>
    </div>
  </div>
</div>

{{-- Filter bar --}}
<form method="GET" class="mt-6 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-4 grid sm:grid-cols-5 gap-3">
  <input type="text" name="q" value="{{ $q }}" placeholder="Cari judul event…"
         class="sm:col-span-3 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
  <select name="status" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
    <option value="">Semua Status</option>
    @foreach (['applied'=>'Applied','approved'=>'Approved','waitlisted'=>'Waitlisted','rejected'=>'Rejected','checked_in'=>'Checked In','completed'=>'Completed','cancelled'=>'Cancelled'] as $k=>$label)
      <option value="{{ $k }}" @selected($status===$k)>{{ $label }}</option>
    @endforeach
  </select>
  <div class="sm:col-span-1 flex gap-2">
    <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">Filter</button>
    <a href="{{ route('registrations.index') }}" class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Reset</a>
  </div>
</form>

{{-- Status chips ringkas --}}
@php
  $statusLabels = ['applied'=>'Applied','approved'=>'Approved','waitlisted'=>'Waitlisted','rejected'=>'Rejected','checked_in'=>'Checked In','completed'=>'Completed','cancelled'=>'Cancelled'];
@endphp
<div class="mt-3 flex flex-wrap gap-2">
  @foreach($statusLabels as $k=>$label)
    <a href="{{ route('registrations.index',['status'=>$k]+request()->except('page')) }}"
       class="text-xs px-3 py-1 rounded-full ring-1
       {{ $status===$k ? 'bg-indigo-600 text-white ring-indigo-600' : 'bg-white text-gray-700 ring-gray-200 hover:bg-gray-50' }}">
      {{ $label }} <span class="opacity-70">· {{ $counts[$k] ?? 0 }}</span>
    </a>
  @endforeach
</div>

{{-- Cards --}}
<div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
  @forelse ($regs as $r)
    @php
      $e = $r->event;
      $badge = [
        'applied'   => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
        'approved'  => 'bg-green-50 text-green-700 ring-green-200',
        'waitlisted'=> 'bg-blue-50 text-blue-700 ring-blue-200',
        'rejected'  => 'bg-red-50 text-red-700 ring-red-200',
        'checked_in'=> 'bg-indigo-50 text-indigo-700 ring-indigo-200',
        'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'cancelled' => 'bg-gray-100 text-gray-700 ring-gray-300',
      ][$r->status] ?? 'bg-gray-100 text-gray-700 ring-gray-300';
    @endphp

    <div class="bg-white rounded-2xl shadow-md ring-1 ring-gray-200 overflow-hidden flex flex-col">
      <a href="{{ route('events.show', $e->slug) }}"><img src="{{ $e->banner_url }}" class="w-full h-40 object-cover" loading="lazy"></a>
      <div class="p-4 flex-1">
        <div class="flex items-start justify-between gap-3">
          <h3 class="font-semibold leading-tight">{{ $e->title }}</h3>
          <span class="inline-flex px-2 py-0.5 text-xs rounded-full ring-1 {{ $badge }}">
            {{ ucfirst(str_replace('_',' ',$r->status)) }}
          </span>
        </div>

        @if($e->category)
          <div class="mt-1">
            <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200">
              {{ $e->category->name }}
            </span>
          </div>
        @endif

        <ul class="mt-3 space-y-1.5 text-sm text-gray-700">
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M7 2v4M17 2v4M3 10h18M5 6h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="1.5"/></svg>
            {{ $e->date_human }} · {{ $e->time_range }}
          </li>
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 21s7-4.35 7-10a7 7 0 1 0-14 0c0 5.65 7 10 7 10z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="11" r="2.5" stroke="currentColor" stroke-width="1.5"/></svg>
            {{ $e->location_human }}
          </li>
        </ul>
      </div>

      <div class="px-4 pb-4 flex items-center gap-2">
        <a href="{{ route('registrations.show', $r) }}"
           class="flex-1 text-center rounded-xl border border-indigo-200 text-indigo-700 hover:bg-indigo-50 px-3 py-2">
           Detail
        </a>
        @if($r->cancellable())
          <form method="POST" action="{{ route('registrations.cancel', $r) }}" onsubmit="return confirm('Batalkan pendaftaran?');">
            @csrf @method('DELETE')
            <button class="px-3 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700">Batalkan</button>
          </form>
        @else
          <button disabled class="px-3 py-2 rounded-xl border border-gray-200 text-gray-500">Tidak bisa batalkan</button>
        @endif
      </div>
    </div>
  @empty
    <div class="sm:col-span-2 lg:col-span-3">
      <div class="rounded-2xl bg-white ring-1 ring-gray-200 p-8 text-center text-gray-600">
        Belum ada pendaftaran. <a class="text-indigo-700 hover:underline" href="{{ route('events.index') }}">Jelajahi event</a>.
      </div>
    </div>
  @endforelse
</div>

<div class="mt-6">{{ $regs->links() }}</div>
@endsection
