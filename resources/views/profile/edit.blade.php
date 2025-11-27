@extends('layouts.dashboard')

@section('content')
@if (session('status'))
  <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
    {{ session('status') }}
  </div>
@endif

{{-- Hero profil --}}
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-sky-500 text-white">
  <div class="p-6 sm:p-8 flex items-center gap-5">
    <div class="relative">
      <img id="avatarPreview" src="{{ auth()->user()->avatar_url }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover ring-4 ring-white/20" alt="avatar">
      <!-- tombol pensil -->
      <button type="button" onclick="document.getElementById('avatar-input').click()"
        class="absolute -bottom-1 -right-1 p-2 rounded-full bg-white text-gray-700 shadow-md hover:bg-gray-100"
        title="Ganti foto">
        <!-- icon pencil -->
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
          <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z" stroke="currentColor" stroke-width="1.5"/>
          <path d="M14.06 4.44 19.56 9.94" stroke="currentColor" stroke-width="1.5"/>
        </svg>
      </button>
    </div>
    <div>
      <div class="text-white/90 text-sm">User Profile</div>
      <h1 class="text-2xl font-semibold">{{ auth()->user()->name }}</h1>
      <div class="text-white/80 text-sm">{{ auth()->user()->email }}</div>
    </div>
  </div>
</div>

{{-- Form --}}
<div class="mt-6 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
  <form id="profile-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="grid gap-5 sm:grid-cols-2">
    @csrf @method('PATCH')

    <input id="avatar-input" type="file" name="avatar" accept="image/*" class="hidden"
           onchange="previewAndSubmit(this)">

    <div>
      <label class="block text-sm font-medium">Nama</label>
      <input name="name" value="{{ old('name', auth()->user()->name) }}" class="mt-1 w-full rounded-xl border-gray-300">
      @error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-medium">Email</label>
      <input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="mt-1 w-full rounded-xl border-gray-300">
      @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-medium">WhatsApp</label>
      <input name="whatsapp" value="{{ old('whatsapp', auth()->user()->whatsapp) }}" class="mt-1 w-full rounded-xl border-gray-300">
      @error('whatsapp')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-medium">Kota/Domisili</label>
      <input name="city" value="{{ old('city', auth()->user()->city) }}" class="mt-1 w-full rounded-xl border-gray-300">
      @error('city')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
      <label class="block text-sm font-medium">Alamat</label>
      <textarea name="address" rows="2" class="mt-1 w-full rounded-xl border-gray-300">{{ old('address', auth()->user()->address) }}</textarea>
      @error('address')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
      <label class="block text-sm font-medium">Bio</label>
      <textarea name="bio" rows="4" class="mt-1 w-full rounded-xl border-gray-300">{{ old('bio', auth()->user()->bio) }}</textarea>
      @error('bio')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
      <label class="block text-sm font-medium">Minat (pisahkan dengan koma)</label>
      <input name="interests" value="{{ old('interests', implode(', ', auth()->user()->interests ?? [])) }}" class="mt-1 w-full rounded-xl border-gray-300">
      @error('interests')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
      <button class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">Simpan Perubahan</button>
    </div>
  </form>
</div>

<script>
function previewAndSubmit(input){
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
    reader.readAsDataURL(input.files[0]);
  }
  // otomatis submit setelah pilih foto
  document.getElementById('profile-form').submit();
}
</script>
@endsection
