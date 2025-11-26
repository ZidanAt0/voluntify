@extends('layouts.dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Event</h1>

<form method="POST"
      action="{{ route('organizer.events.update', $event->id) }}"
      enctype="multipart/form-data"
      class="bg-white p-6 rounded-2xl shadow ring-1 ring-gray-200 space-y-4">

    @csrf
    @method('PUT')

    <div>
        <label class="text-sm font-medium">Judul Event</label>
        <input name="title"
               value="{{ old('title', $event->title) }}"
               class="w-full mt-1 rounded border-gray-300" required>
    </div>

    <div>
        <label class="text-sm font-medium">Deskripsi</label>
        <textarea name="description"
                  rows="4"
                  class="w-full mt-1 rounded border-gray-300">{{ old('description', $event->description) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium">Mulai</label>
            <input type="datetime-local"
                   name="starts_at"
                   value="{{ old('starts_at', \Carbon\Carbon::parse($event->starts_at)->format('Y-m-d\TH:i')) }}"
                   class="w-full mt-1 rounded border-gray-300" required>
        </div>

        <div>
            <label class="text-sm font-medium">Selesai</label>
            <input type="datetime-local"
                   name="ends_at"
                   value="{{ old('ends_at', \Carbon\Carbon::parse($event->ends_at)->format('Y-m-d\TH:i')) }}"
                   class="w-full mt-1 rounded border-gray-300" required>
        </div>
    </div>

    <div>
        <label class="text-sm font-medium">Kapasitas</label>
        <input type="number"
               name="capacity"
               value="{{ old('capacity', $event->capacity) }}"
               class="w-full mt-1 rounded border-gray-300">
    </div>

    <div>
        <label class="text-sm font-medium">Banner</label>
        @if($event->banner_path)
            <img src="{{ asset('storage/'.$event->banner_path) }}"
                 class="w-48 rounded-lg mb-2">
        @endif
        <input type="file" name="banner" class="w-full mt-1">
    </div>

    <div class="pt-4">
        <button class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
            Simpan Perubahan
        </button>

        <a href="{{ route('organizer.events.index') }}" class="ml-3 text-gray-600">
            Batal
        </a>
    </div>
</form>
@endsection
