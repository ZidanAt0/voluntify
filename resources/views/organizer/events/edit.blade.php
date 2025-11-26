@extends('layouts.dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Event</h1>

<form method="POST" action="{{ route('organizer.events.update', $event->id) }}"
      class="bg-white p-6 rounded-2xl shadow ring-1 ring-gray-200 space-y-4">
    @csrf @method('PUT')

    <div>
        <label class="text-sm font-medium">Judul Event</label>
        <input name="title" value="{{ old('title', $event->title) }}"
               class="w-full mt-1 rounded border-gray-300" required>
    </div>

    <div>
        <label class="text-sm font-medium">Deskripsi</label>
        <textarea name="description" rows="4"
                  class="w-full mt-1 rounded border-gray-300">{{ old('description', $event->description) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium">Mulai</label>
            <input type="datetime-local" name="starts_at"
                   value="{{ old('starts_at', $event->starts_at->format('Y-m-d\TH:i')) }}"
                   class="w-full mt-1 rounded border-gray-300" required>
        </div>

        <div>
            <label class="text-sm font-medium">Selesai</label>
            <input type="datetime-local" name="ends_at"
                   value="{{ old('ends_at', $event->ends_at->format('Y-m-d\TH:i')) }}"
                   class="w-full mt-1 rounded border-gray-300" required>
        </div>
    </div>

    <div>
        <label class="text-sm font-medium">Kapasitas</label>
        <input type="number" name="capacity"
               value="{{ old('capacity', $event->capacity) }}"
               class="w-full mt-1 rounded border-gray-300">
    </div>

    <div>
        <label class="text-sm font-medium">Kategori</label>
        <select name="category_id" class="w-full mt-1 rounded border-gray-300">
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}"
                    @selected($event->category_id == $c->id)>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="text-sm font-medium">Kota</label>
        <input name="city" value="{{ old('city', $event->city) }}"
               class="w-full mt-1 rounded border-gray-300">
    </div>

    <div>
        <label class="text-sm font-medium">Alamat</label>
        <input name="address" value="{{ old('address', $event->address) }}"
               class="w-full mt-1 rounded border-gray-300">
    </div>

    <div class="pt-4 flex gap-3">
        <button class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
            Update Event
        </button>

        <a href="{{ route('organizer.events.index') }}" class="px-6 py-2 border rounded-xl">
            Batal
        </a>
    </div>
</form>
@endsection
