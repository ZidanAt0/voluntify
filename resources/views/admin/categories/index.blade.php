@extends('layouts.dashboard')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Kategori</h1>
    <button type="button"
       class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 text-sm"
       id="openCreateModal">
        Tambah Kategori
    </button>
</div>

@if(session('success'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg p-3">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Slug</th>
                <th class="p-3 text-center">Event Terdaftar</th>
                <th class="p-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($categories as $cat)
                <tr>
                    <td class="p-3">{{ $cat->name }}</td>
                    <td class="p-3 text-gray-500">{{ $cat->slug }}</td>
                    <td class="p-3 text-center text-gray-700">{{ $cat->events_count ?? 0 }}</td>
                    <td class="p-3 text-right flex items-center justify-end gap-2">
                        <button type="button"
                           class="px-3 py-1 text-xs rounded bg-white border border-gray-200 hover:bg-gray-50 js-edit"
                           data-id="{{ $cat->id }}"
                           data-name="{{ $cat->name }}"
                           data-slug="{{ $cat->slug }}"
                           data-action="{{ route('admin.categories.update', $cat) }}">
                            Edit
                        </button>
                        @if(($cat->events_count ?? 0) > 0)
                            <span class="px-3 py-1 text-xs rounded bg-gray-100 text-gray-500 border border-gray-200 cursor-not-allowed">
                                Tidak bisa dihapus
                            </span>
                        @else
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1 text-xs rounded bg-red-50 text-red-700 border border-red-200 hover:bg-red-100">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-4 text-center text-gray-500">Belum ada kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $categories->links() }}
</div>

{{-- Modal Create --}}
<div id="createModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b">
            <h3 class="text-lg font-semibold">Tambah Kategori</h3>
            <button id="closeCreateModal" class="text-gray-500 hover:text-gray-800">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="p-4 space-y-2">
            @csrf
            <div>
                <label class="text-sm font-medium">Nama</label>
                <input type="text" name="name" class="w-full mt-1 rounded border-gray-300" required>
            </div>
            <div>
                <label class="text-sm font-medium">Slug (opsional)</label>
                <input type="text" name="slug" class="w-full mt-1 rounded border-gray-300">
            </div>
            <div class="flex gap-4">
                <button class="px-5 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">Simpan</button>
                <button type="button" id="cancelCreateModal" class="px-5 py-2 border rounded-xl">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b">
            <h3 class="text-lg font-semibold">Edit Kategori</h3>
            <button id="closeEditModal" class="text-gray-500 hover:text-gray-800">✕</button>
        </div>
        <form method="POST" id="editForm" class="p-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="text-sm font-medium">Nama</label>
                <input type="text" name="name" id="editName" class="w-full mt-1 rounded border-gray-300" required>
            </div>
            <div>
                <label class="text-sm font-medium">Slug (opsional)</label>
                <input type="text" name="slug" id="editSlug" class="w-full mt-1 rounded border-gray-300">
            </div>
            <div class="flex gap-3">
                <button class="px-5 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">Simpan</button>
                <button type="button" id="cancelEditModal" class="px-5 py-2 border rounded-xl">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    const openCreate = document.getElementById('openCreateModal');
    const closeCreate = document.getElementById('closeCreateModal');
    const cancelCreate = document.getElementById('cancelCreateModal');
    const closeEdit = document.getElementById('closeEditModal');
    const cancelEdit = document.getElementById('cancelEditModal');
    const editForm = document.getElementById('editForm');
    const editName = document.getElementById('editName');
    const editSlug = document.getElementById('editSlug');

    const show = (modal) => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
    const hide = (modal) => { modal.classList.add('hidden'); modal.classList.remove('flex'); };

    openCreate?.addEventListener('click', () => show(createModal));
    closeCreate?.addEventListener('click', () => hide(createModal));
    cancelCreate?.addEventListener('click', () => hide(createModal));

    closeEdit?.addEventListener('click', () => hide(editModal));
    cancelEdit?.addEventListener('click', () => hide(editModal));

    document.querySelectorAll('.js-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            editName.value = btn.dataset.name || '';
            editSlug.value = btn.dataset.slug || '';
            editForm.action = btn.dataset.action;
            show(editModal);
        });
    });

    [createModal, editModal].forEach(modal => {
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) hide(modal);
        });
    });
});
</script>
@endpush
@endsection
