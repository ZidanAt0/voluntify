@extends('layouts.admin')

@section('content')
<div class="max-w-xl">
    <h1 class="text-2xl font-bold mb-6">Tambah Kategori</h1>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg p-3">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.store') }}"
          class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6 space-y-4">
        @csrf
        <div>
            <label class="text-sm font-medium">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full mt-1 rounded border-gray-300" required>
        </div>
        <div>
            <label class="text-sm font-medium">Slug (opsional)</label>
            <input type="text" name="slug" value="{{ old('slug') }}"
                   class="w-full mt-1 rounded border-gray-300">
        </div>
        <div class="flex gap-3 pt-2">
            <button class="px-5 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
                Simpan
            </button>
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2 border rounded-xl">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
