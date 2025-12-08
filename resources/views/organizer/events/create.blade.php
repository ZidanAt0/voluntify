@extends('layouts.dashboard')

@section('content')
<div class="mb-10">
    <h1
        class="text-5xl font-extrabold bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-400
               bg-clip-text text-transparent drop-shadow-lg tracking-tight">
        Buat Event Baru
    </h1>
    <p class="text-gray-500 mt-2 text-sm opacity-80">
        Isi detail event dengan tampilan modern bertema biru
    </p>
</div>

<form method="POST" action="{{ route('organizer.events.store') }}" enctype="multipart/form-data"
      class="backdrop-blur-xl bg-white/70 p-10 rounded-3xl shadow-[0_15px_50px_rgba(15,23,42,0.08)]
             border border-blue-50 space-y-8 transition-all duration-300 hover:shadow-[0_20px_60px_rgba(15,23,42,0.12)]">

    @csrf

    {{-- Banner Upload --}}
    <div class="space-y-2">
        <label class="text-sm font-semibold text-gray-700">Banner Event</label>
        <input type="file" name="banner" accept="image/*" id="bannerInput" class="hidden">

        <label for="bannerInput" class="group block cursor-pointer">
            <div id="bannerPlaceholder"
                 class="h-60 flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300
                        bg-gradient-to-br from-gray-50 to-gray-100/70 text-gray-500 transition-all duration-300
                        group-hover:border-indigo-500 group-hover:from-indigo-50 group-hover:to-cyan-50
                        group-hover:shadow-[0_15px_40px_rgba(37,99,235,0.25)] hover:scale-105">
                <div
                    class="w-16 h-16 flex items-center justify-center rounded-full bg-gray-400/40 
                           text-white text-3xl transition-all group-hover:bg-indigo-600 shadow-md backdrop-blur">
                    +
                </div>
                <div class="mt-3 font-medium text-sm">Klik untuk upload banner</div>
            </div>
        </label>

        <div id="bannerPreviewContainer" class="hidden relative mt-3">
            <img id="bannerPreview"
                 class="h-60 w-full object-cover rounded-2xl border border-blue-100 shadow-xl 
                        transition-transform duration-500 hover:scale-105">

            <div class="absolute bottom-4 right-4 flex gap-3">
                <button type="button" id="bannerCropBtn"
                    class="w-11 h-11 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-xl shadow-lg
                           hover:bg-indigo-600 hover:text-white transition border text-lg transform hover:scale-110">
                    ✂
                </button>
                <button type="button" id="bannerRemoveBtn"
                    class="w-11 h-11 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-xl shadow-lg
                           hover:bg-red-600 hover:text-white transition border text-lg transform hover:scale-110">
                    🗑
                </button>
            </div>
        </div>
    </div>

    @php
        // input style biru
        $inputClass = "w-full rounded-2xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 
                       bg-gray-50 hover:bg-white transition-all duration-300 shadow-sm p-4";
    @endphp

    <div class="space-y-2">
        <label class="text-sm font-bold text-gray-700">Judul Event</label>
        <input name="title" required class="{{ $inputClass }}">
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-gray-700">Ringkasan Singkat</label>
        <input name="excerpt" class="{{ $inputClass }}">
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-gray-700">Deskripsi Lengkap</label>
        <textarea name="description" rows="4" class="{{ $inputClass }}"></textarea>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-gray-700">Kategori</label>
        <select name="category_id" class="{{ $inputClass }}">
            <option value="">Pilih Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="space-y-2">
            <label class="text-sm font-bold text-gray-700">Mulai</label>
            <input type="datetime-local" name="starts_at" required class="{{ $inputClass }}">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-bold text-gray-700">Selesai</label>
            <input type="datetime-local" name="ends_at" required class="{{ $inputClass }}">
        </div>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-gray-700">Kapasitas Peserta</label>
        <input type="number" name="capacity" min="1" class="{{ $inputClass }}">
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-gray-700">Status Event</label>
        <select name="status" required class="{{ $inputClass }}">
            <option value="draft">Draft</option>
            <option value="published">Publish</option>
            <option value="closed">Closed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    {{-- Action Buttons --}}
    <div class="pt-5 flex items-center gap-4">
        <button
            class="px-10 py-3.5 bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-400
                   text-white font-semibold rounded-2xl shadow-xl hover:shadow-2xl hover:scale-[1.05] 
                   transition-all duration-300">
            Simpan Event
        </button>
        <a href="{{ route('organizer.events.index') }}"
           class="text-gray-600 hover:text-gray-900 hover:underline transition duration-300">
            Batal
        </a>
    </div>
</form>

@push('scripts')
{!! $script ?? '' !!}
@endpush
@endsection