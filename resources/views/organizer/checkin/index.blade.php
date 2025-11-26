@extends('layouts.dashboard')

@section('content')
<div class="max-w-xl mx-auto mt-12">

    <h1 class="text-2xl font-bold mb-6 text-center">Check-in Peserta</h1>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('organizer.checkin.store') }}"
          class="bg-white p-6 rounded-xl shadow ring-1 ring-gray-200 space-y-4">
        @csrf

        <div>
            <label class="text-sm font-medium">Kode Unik Peserta</label>
            <input type="text" name="checkin_code"
                   class="w-full mt-1 rounded border-gray-300 text-center text-lg tracking-widest"
                   placeholder="Contoh: 482931"
                   required>
        </div>

        <button class="w-full py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
            Validasi Check-in
        </button>
    </form>
</div>
@endsection
