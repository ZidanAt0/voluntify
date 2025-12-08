@extends('layouts.dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">Buat Event</h1>

<form method="POST"
      action="{{ route('organizer.events.store') }}"
      enctype="multipart/form-data"
      class="bg-white p-6 rounded-2xl shadow ring-1 ring-gray-200 space-y-5">
    @csrf

    {{-- BANNER --}}
    <div>
        <label class="text-sm font-medium">Banner Event</label>
        <div class="mt-2">
            <input type="file" name="banner" accept="image/*" id="bannerInput" class="hidden">

            <label for="bannerInput" class="group cursor-pointer block">
                <div id="bannerPlaceholder"
                     class="h-48 flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 text-gray-600 bg-gray-50 transition group-hover:border-indigo-500 group-hover:text-indigo-600">
                    <div class="w-12 h-12 mb-3 flex items-center justify-center rounded-full bg-gray-400 text-white text-2xl transition group-hover:bg-indigo-600">+</div>
                    <div class="text-sm font-medium">Tambah Banner</div>
                </div>
            </label>

            <div id="bannerPreviewContainer" class="hidden relative">
                <img id="bannerPreview"
                     data-placeholder="https://placehold.co/800x450?text=Preview+Banner"
                     src="https://placehold.co/800x450?text=Preview+Banner"
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

    {{-- CROP MODAL --}}
    <div id="cropModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4">
        <div class="bg-white w-full max-w-4xl rounded-xl shadow-2xl overflow-hidden flex flex-col">
            <div class="px-6 py-4 flex items-center justify-between border-b">
                <h3 class="text-lg font-semibold">Atur Crop Banner</h3>
                <button type="button" id="cropCloseBtn" class="text-gray-500 hover:text-gray-800">✕</button>
            </div>

            <div class="px-6 py-4">
                <div id="cropViewport" class="relative w-full aspect-[16/9] bg-gray-100 overflow-hidden rounded-lg border border-gray-200">
                    <img id="cropImage" class="absolute inset-0 m-auto select-none" alt="Crop preview" style="transform-origin: center center;">
                </div>

                <div class="mt-4 flex items-center gap-3">
                    <span class="text-sm text-gray-600">Zoom</span>
                    <input type="range" id="cropZoom" min="100" max="300" value="100" class="flex-1 accent-indigo-600">
                    <span id="cropZoomValue" class="text-sm text-gray-700 w-12 text-right">100%</span>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t">
                <button type="button" id="cropCancelBtn"
                        class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">
                    Batal
                </button>
                <button type="button" id="cropSaveBtn"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    Simpan
                </button>
            </div>
        </div>
    </div>

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
                <option value="draft">Simpan sebagai Draft</option>
                <option value="submit_for_review">Submit untuk Review Admin</option>
        </select>
        <p class="mt-1 text-xs text-gray-500">
            Draft: Event disimpan tapi belum diajukan ke admin. Anda bisa edit lagi nanti.<br>
            Submit untuk Review: Event langsung diajukan ke admin untuk disetujui.
        </p>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('bannerInput');
    const preview = document.getElementById('bannerPreview');
    const placeholder = document.getElementById('bannerPlaceholder');
    const previewContainer = document.getElementById('bannerPreviewContainer');
    const removeBtn = document.getElementById('bannerRemoveBtn');
    const cropBtn = document.getElementById('bannerCropBtn');
    const cropModal = document.getElementById('cropModal');
    const cropImage = document.getElementById('cropImage');
    const cropZoom = document.getElementById('cropZoom');
    const cropZoomValue = document.getElementById('cropZoomValue');
    const cropViewport = document.getElementById('cropViewport');
    const cropCancelBtn = document.getElementById('cropCancelBtn');
    const cropSaveBtn = document.getElementById('cropSaveBtn');
    const cropCloseBtn = document.getElementById('cropCloseBtn');
    if (!input || !preview || !placeholder || !previewContainer || !removeBtn || !cropBtn) return;

    const placeholderUrl = preview.dataset.placeholder || preview.src;
    const cropState = {
        fitScale: 1,
        userScale: 1,
        offsetX: 0,
        offsetY: 0,
        naturalWidth: 0,
        naturalHeight: 0,
        originalFile: null,
        originalUrl: null,
    };

    input.addEventListener('change', (event) => {
        const file = event.target.files && event.target.files[0];
        if (!file) {
            preview.src = placeholderUrl;
            preview.classList.add('hidden');
            previewContainer.classList.add('hidden');
            placeholder.classList.remove('hidden');
            cropState.originalFile = null;
            cropState.originalUrl = null;
            return;
        }
        cropState.originalFile = file;
        cropState.originalUrl = URL.createObjectURL(file);
        preview.src = cropState.originalUrl;
        placeholder.classList.add('hidden');
        previewContainer.classList.remove('hidden');
    });

    removeBtn.addEventListener('click', () => {
        input.value = '';
        preview.src = placeholderUrl;
        previewContainer.classList.add('hidden');
        placeholder.classList.remove('hidden');
        cropState.originalFile = null;
        cropState.originalUrl = null;
    });

    const applyCropTransform = () => {
        const scale = cropState.fitScale * cropState.userScale;
        cropImage.style.transform = `translate(${cropState.offsetX}px, ${cropState.offsetY}px) scale(${scale})`;
    };

    const showCropModal = () => {
        cropModal.classList.remove('hidden');
        cropModal.classList.add('flex');
    };

    const hideCropModal = () => {
        cropModal.classList.add('hidden');
        cropModal.classList.remove('flex');
    };

    const openCropModal = () => {
        if (!cropState.originalFile || !cropState.originalUrl) return;
        cropImage.onload = () => {
            cropState.naturalWidth = cropImage.naturalWidth;
            cropState.naturalHeight = cropImage.naturalHeight;
            showCropModal();
            requestAnimationFrame(() => {
                const viewportRect = cropViewport.getBoundingClientRect();
                cropState.fitScale = Math.max(
                    viewportRect.width / cropState.naturalWidth,
                    viewportRect.height / cropState.naturalHeight
                );
                cropState.userScale = 1;
                cropState.offsetX = 0;
                cropState.offsetY = 0;
                cropZoom.value = 100;
                cropZoomValue.textContent = '100%';
                applyCropTransform();
            });
        };
        cropImage.src = cropState.originalUrl;
    };

    cropBtn.addEventListener('click', (e) => {
        e.preventDefault();
        openCropModal();
    });

    const closeCropModal = () => {
        hideCropModal();
    };

    cropCancelBtn?.addEventListener('click', closeCropModal);
    cropCloseBtn?.addEventListener('click', closeCropModal);

    cropZoom?.addEventListener('input', (e) => {
        const value = Number(e.target.value || 100);
        cropState.userScale = value / 100;
        cropZoomValue.textContent = `${value}%`;
        applyCropTransform();
    });

    // drag to pan
    let isPanning = false;
    let startX = 0;
    let startY = 0;
    cropViewport?.addEventListener('mousedown', (e) => {
        isPanning = true;
        startX = e.clientX - cropState.offsetX;
        startY = e.clientY - cropState.offsetY;
    });
    window.addEventListener('mouseup', () => { isPanning = false; });
    window.addEventListener('mousemove', (e) => {
        if (!isPanning) return;
        cropState.offsetX = e.clientX - startX;
        cropState.offsetY = e.clientY - startY;
        applyCropTransform();
    });

    const dataUrlToFile = (dataUrl, filename) => {
        const arr = dataUrl.split(',');
        const mime = arr[0].match(/:(.*?);/)[1];
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);
        while (n--) {
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new File([u8arr], filename, { type: mime });
    };

    const fileNameFromInput = (fileInput) => {
        if (!fileInput.files || !fileInput.files[0]) return null;
        return fileInput.files[0].name;
    };

    cropSaveBtn?.addEventListener('click', () => {
        if (!cropState.naturalWidth || !cropState.naturalHeight) return;
        const viewportRect = cropViewport.getBoundingClientRect();
        const canvas = document.createElement('canvas');
        canvas.width = 1600;
        canvas.height = 900;
        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        const finalScale = cropState.fitScale * cropState.userScale;
        const drawWidth = cropState.naturalWidth * finalScale;
        const drawHeight = cropState.naturalHeight * finalScale;
        const originX = (viewportRect.width - drawWidth) / 2 + cropState.offsetX;
        const originY = (viewportRect.height - drawHeight) / 2 + cropState.offsetY;

        const scaleX = canvas.width / viewportRect.width;
        const scaleY = canvas.height / viewportRect.height;

        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(
            cropImage,
            originX * scaleX,
            originY * scaleY,
            drawWidth * scaleX,
            drawHeight * scaleY
        );

        const dataUrl = canvas.toDataURL('image/jpeg', 0.92);
        preview.src = dataUrl;
        previewContainer.classList.remove('hidden');
        placeholder.classList.add('hidden');

        const file = dataUrlToFile(dataUrl, fileNameFromInput(input) || 'banner-cropped.jpg');
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;

        closeCropModal();
    });
});
</script>
@endpush
@endsection