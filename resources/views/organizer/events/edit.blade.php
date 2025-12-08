@extends('layouts.dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Event</h1>

<form method="POST" action="{{ route('organizer.events.update', $event->id) }}"
      enctype="multipart/form-data"
      class="bg-white p-6 rounded-2xl shadow ring-1 ring-gray-200 space-y-4">
    @csrf @method('PUT')

    @if(session('error'))
        <div class="p-3 rounded-lg bg-green-100 text-green-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- BANNER --}}
    <div>
        <label class="text-sm font-medium">Banner Event</label>
        <div class="mt-2">
            <input type="hidden" name="remove_banner" id="removeBannerFlag" value="0">
            <input type="file" name="banner" accept="image/*" id="bannerInput" class="hidden">

            <label for="bannerInput" class="group cursor-pointer block">
                <div id="bannerPlaceholder"
                     class="h-48 flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 text-gray-600 bg-gray-50 transition group-hover:border-indigo-500 group-hover:text-indigo-600">
                    <div class="w-12 h-12 mb-3 flex items-center justify-center rounded-full bg-gray-400 text-white text-2xl transition group-hover:bg-indigo-600">+</div>
                    <div class="text-sm font-medium">Tambah Banner</div>
                </div>
            </label>

            <div id="bannerPreviewContainer" class="relative {{ $event->banner_path ? '' : 'hidden' }}">
                <img id="bannerPreview"
                     data-placeholder="{{ $event->banner_url }}"
                     data-existing="{{ $event->banner_path ? '1' : '0' }}"
                     src="{{ $event->banner_url }}"
                     class="h-48 w-full object-cover rounded-lg border border-gray-200">

                <div class="absolute bottom-2 right-2 flex gap-2">
                    <button type="button" id="bannerCropBtn"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-white shadow hover:bg-indigo-600 hover:text-white transition border border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6v12m0 0h12M6 6h12M9 3v3m6 9v3m-9-9h3m6 0h3" />
                        </svg>
                    </button>
                    <button type="button" id="bannerRemoveBtn"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-white shadow hover:bg-red-600 hover:text-white transition border border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0h8l-.867-2.6A1 1 0 0015.182 3H8.818a1 1 0 00-.951.684L7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div>
        <label class="text-sm font-medium">Judul Event</label>
        <input name="title" value="{{ old('title', $event->title) }}"
               class="w-full mt-1 rounded border-gray-300" required>
    </div>

    <div>
        <label class="text-sm font-medium">Ringkasan Singkat</label>
        <input name="excerpt" value="{{ old('excerpt', $event->excerpt) }}"
               class="w-full mt-1 rounded border-gray-300">
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
        <label class="text-sm font-medium">Tipe Lokasi</label>
        <select name="location_type" class="w-full mt-1 rounded border-gray-300" required>
            <option value="onsite" @selected(old('location_type', $event->location_type) === 'onsite')>Onsite</option>
            <option value="online" @selected(old('location_type', $event->location_type) === 'online')>Online</option>
            <option value="hybrid" @selected(old('location_type', $event->location_type) === 'hybrid')>Hybrid</option>
        </select>
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

    <div>
        <label class="text-sm font-medium">Status Event</label>
        <select name="status" class="w-full mt-1 rounded border-gray-300" required>
            <option value="draft" @selected(old('status', $event->status) === 'draft')>Draft</option>
            <option value="published" @selected(old('status', $event->status) === 'published')>Publish</option>
            <option value="closed" @selected(old('status', $event->status) === 'closed')>Closed</option>
            <option value="cancelled" @selected(old('status', $event->status) === 'cancelled')>Cancelled</option>
        </select>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('bannerInput');
    const preview = document.getElementById('bannerPreview');
    const placeholder = document.getElementById('bannerPlaceholder');
    const previewContainer = document.getElementById('bannerPreviewContainer');
    const removeBtn = document.getElementById('bannerRemoveBtn');
    const cropBtn = document.getElementById('bannerCropBtn');
    const removeFlag = document.getElementById('removeBannerFlag');
    if (!input || !preview || !placeholder || !previewContainer || !removeBtn || !cropBtn || !removeFlag) return;

    const placeholderUrl = preview.dataset.placeholder || preview.src;
    const hasExisting = preview.dataset.existing === '1';

    if (hasExisting) {
        placeholder.classList.add('hidden');
        previewContainer.classList.remove('hidden');
    }

    input.addEventListener('change', (event) => {
        const file = event.target.files && event.target.files[0];
        removeFlag.value = '0';
        if (!file) {
            if (hasExisting) {
                preview.src = placeholderUrl;
                previewContainer.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                preview.src = placeholderUrl;
                previewContainer.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
            return;
        }
        const tempUrl = URL.createObjectURL(file);
        preview.src = tempUrl;
        placeholder.classList.add('hidden');
        previewContainer.classList.remove('hidden');
    });

    removeBtn.addEventListener('click', () => {
        input.value = '';
        preview.src = placeholderUrl;
        previewContainer.classList.add('hidden');
        placeholder.classList.remove('hidden');
        removeFlag.value = '1';
    });

    cropBtn.addEventListener('click', (e) => {
        e.preventDefault();
        alert('Crop hanya tersedia saat memilih file baru.');
    });
});
</script>
@endpush
@endsection
