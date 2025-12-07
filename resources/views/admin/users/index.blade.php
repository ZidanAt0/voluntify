@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Manajemen User</h1>

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

<form method="GET" class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-4 flex flex-col sm:flex-row gap-3 mb-4">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama/email"
           class="flex-1 rounded border-gray-300">
    <select name="role" class="rounded border-gray-300">
        <option value="">Semua Role</option>
        <option value="user" @selected($role==='user')>User</option>
        <option value="organizer" @selected($role==='organizer')>Organizer</option>
        <option value="admin" @selected($role==='admin')>Admin</option>
    </select>
    <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Filter</button>
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded border border-gray-200">Reset</a>
</form>

<div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">User</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Role</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($users as $user)
                <tr>
                    <td class="p-3 flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-200">
                        <div>
                            <div class="font-semibold">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">Joined {{ $user->created_at?->format('d M Y') }}</div>
                        </div>
                    </td>
                    <td class="p-3">{{ $user->email }}</td>
                    <td class="p-3">
                        @foreach($user->roles as $r)
                            <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-xs">{{ $r->name }}</span>
                        @endforeach
                    </td>
                    <td class="p-3 text-sm">
                        @if($user->organizer_verified_at)
                            <span class="px-2 py-0.5 rounded-full bg-green-50 text-green-700 text-xs">Organizer Verified</span>
                        @endif
                        @if($user->suspended_at)
                            <span class="px-2 py-0.5 rounded-full bg-red-50 text-red-700 text-xs">Suspended</span>
                        @endif
                    </td>
                    <td class="p-3 text-right space-y-1">
                        <form method="POST" action="{{ route('admin.users.verifyOrganizer', $user) }}" class="inline">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1 text-xs rounded bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100">
                                Verify Organizer
                            </button>
                        </form>

                        @if($user->suspended_at)
                            <form method="POST" action="{{ route('admin.users.unsuspend', $user) }}" class="inline">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1 text-xs rounded bg-green-50 text-green-700 border border-green-200 hover:bg-green-100">
                                    Unsuspend
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.suspend', $user) }}" class="inline">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1 text-xs rounded bg-red-50 text-red-700 border border-red-200 hover:bg-red-100">
                                    Suspend
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" class="inline-flex items-center gap-2 mt-1">
                            @csrf @method('PATCH')
                            <select name="role" class="rounded border-gray-300 text-xs">
                                <option value="user" @selected($user->hasRole('user'))>User</option>
                                <option value="organizer" @selected($user->hasRole('organizer'))>Organizer</option>
                                <option value="admin" @selected($user->hasRole('admin'))>Admin</option>
                            </select>
                            <button class="px-3 py-1 text-xs rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Ubah Role
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
