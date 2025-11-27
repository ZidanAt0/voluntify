@extends('layouts.dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">Buat Event</h1>

<form method="POST"
      action="{{ route('organizer.events.store') }}"
      class="bg-white p-6 rounded-2xl shadow ring-1 ring-gray-200 space-y-5">
    @csrf

    {{-- JUDUL --}}
    <div>
        <label class="text-sm font-medium">Judul Event</label>
        <input name="title" required
               class="w-full mt-1 rounded border-gray-300">
    </div>

    {{-- EXCERPT (OPSIONAL DI DB, TAPI ADA) --}}
    <div>
        <label class="text-sm font-medium">Ringkasan Singkat</label>
        <input name="excerpt"
               class="w-full mt-1 rounded border-gray-300">
    </div>

    {{-- DESKRIPSI --}}
    <div>
        <label class="text-sm font-medium">Deskripsi Lengkap</label>
        <textarea name="description" rows="4"
                  class="w-full mt-1 rounded border-gray-300"></textarea>
    </div>

    {{-- KATEGORI (SESUAI FK) --}}
    <div>
        <label class="text-sm font-medium">Kategori</label>
        <select name="category_id" class="w-full mt-1 rounded border-gray-300">
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- TIPE LOKASI --}}
    <div>
        <label class="text-sm font-medium">Tipe Lokasi</label>
        <select name="location_type" required
                class="w-full mt-1 rounded border-gray-300">
            <option value="onsite">Onsite</option>
            <option value="online">Online</option>
            <option value="hybrid">Hybrid</option>
        </select>
    </div>

    {{-- KOTA --}}
    <div>
        <label class="text-sm font-medium">Kota</label>
        <input name="city"
               class="w-full mt-1 rounded border-gray-300">
    </div>

    {{-- ALAMAT --}}
    <div>
        <label class="text-sm font-medium">Alamat Lengkap</label>
        <input name="address"
               class="w-full mt-1 rounded border-gray-300">
    </div>

    {{-- TANGGAL --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium">Mulai</label>
            <input type="datetime-local" name="starts_at" required
                   class="w-full mt-1 rounded border-gray-300">
        </div>

        <div>
            <label class="text-sm font-medium">Selesai</label>
            <input type="datetime-local" name="ends_at" required
                   class="w-full mt-1 rounded border-gray-300">
        </div>
    </div>

    {{-- KAPASITAS --}}
    <div>
        <label class="text-sm font-medium">Kapasitas Peserta</label>
        <input type="number" name="capacity" min="1"
               class="w-full mt-1 rounded border-gray-300">
    </div>

    {{-- STATUS --}}
    <div>
        <label class="text-sm font-medium">Status Event</label>
        <select name="status" required
                class="w-full mt-1 rounded border-gray-300">
            <option value="draft">Draft</option>
            <option value="published">Publish</option>
            <option value="closed">Closed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    {{-- SUBMIT --}}
    <div class="pt-4 flex items-center gap-3">
        <button class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
            Simpan Event
        </button>

        <a href="{{ route('organizer.events.index') }}" class="text-gray-600">
            Batal
        </a>
    </div>
</form>
@endsection
