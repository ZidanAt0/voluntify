@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Kategori</h1>
    <a href="{{ route('admin.categories.create') }}"
       class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 text-sm">
        Tambah Kategori
    </a>
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
                <th class="p-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($categories as $cat)
                <tr>
                    <td class="p-3">{{ $cat->name }}</td>
                    <td class="p-3 text-gray-500">{{ $cat->slug }}</td>
                    <td class="p-3 text-right flex items-center justify-end gap-2">
                        <a href="{{ route('admin.categories.edit', $cat) }}"
                           class="px-3 py-1 text-xs rounded bg-white border border-gray-200 hover:bg-gray-50">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 text-xs rounded bg-red-50 text-red-700 border border-red-200 hover:bg-red-100">
                                Hapus
                            </button>
                        </form>
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
@endsection
