@extends('layouts.dashboard')

@section('content')
<h1 class="text-2xl font-bold">Pendaftaran Saya</h1>
@if (session('status'))
    <div class="mt-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
        {{ session('status') }}
    </div>
@endif

<div class="mt-6 grid gap-6">
@forelse ($regs as $r)
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-4 flex items-start gap-4">
        <img src="{{ $r->event->banner_url }}" class="w-32 h-20 object-cover rounded-lg" alt="">
        <div class="flex-1">
            <a href="{{ route('registrations.show', $r) }}" class="font-semibold hover:underline">
                {{ $r->event->title }}
            </a>
            <div class="text-sm text-gray-600 mt-1">
                {{ $r->event->date_human }} · {{ $r->event->time_range }} · {{ $r->event->location_human }}
            </div>
            <div class="mt-2">
                @php
                    $badges = [
                        'applied'=>'bg-yellow-50 text-yellow-700 ring-yellow-200',
                        'approved'=>'bg-green-50 text-green-700 ring-green-200',
                        'waitlisted'=>'bg-blue-50 text-blue-700 ring-blue-200',
                        'rejected'=>'bg-red-50 text-red-700 ring-red-200',
                        'cancelled'=>'bg-gray-100 text-gray-700 ring-gray-300',
                        'checked_in'=>'bg-indigo-50 text-indigo-700 ring-indigo-200',
                        'completed'=>'bg-emerald-50 text-emerald-700 ring-emerald-200',
                    ];
                @endphp
                <span class="inline-flex items-center px-2.5 py-1 rounded-full ring-1 {{ $badges[$r->status] ?? 'bg-gray-100 text-gray-700 ring-gray-300' }}">
                    {{ ucfirst(str_replace('_',' ',$r->status)) }}
                </span>
            </div>
        </div>
        <a href="{{ route('registrations.show', $r) }}"
           class="px-3 py-2 rounded-xl border border-indigo-200 text-indigo-700 hover:bg-indigo-50">
           Detail
        </a>
    </div>
@empty
    <div class="text-gray-600">Belum ada pendaftaran.</div>
@endforelse
</div>

<div class="mt-6">{{ $regs->links() }}</div>
@endsection
