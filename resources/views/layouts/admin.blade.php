@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Admin' }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">

<header class="bg-white border-b">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
    <a href="{{ url('/') }}" class="text-xl font-semibold">Voluntify</a>

    <nav class="flex items-center gap-3">
      @auth
      <a href="{{ route('events.index') }}" class="text-sm text-gray-700 hover:text-indigo-600">Jelajah</a>
      <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-indigo-600">Dashboard</a>
      @endauth

      @auth
      <details class="relative group">
        <summary class="list-none flex items-center gap-2 cursor-pointer">
          <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full object-cover" alt="avatar">
          <span class="hidden sm:inline text-sm text-gray-700">{{ Str::limit(auth()->user()->name, 18) }}</span>
          <svg class="w-4 h-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor"><path d="M5.5 7.5 10 12l4.5-4.5"/></svg>
        </summary>
        <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-gray-200 p-2 z-50">
        <div class="px-2 pb-0 flex items-center"><img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full object-cover" alt="avatar">
        <div class="px-3 py-2 text-xs text-gray-500">Masuk sebagai<br><span class="font-medium text-gray-800">{{ auth()->user()->email }}</span></div></div>
          <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-50 text-sm {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">Kategori</a>
            <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-50 text-sm {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">Users</a>
            <a href="{{ route('admin.events.moderation.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-50 text-sm {{ request()->routeIs('admin.events.moderation.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">Moderasi Event</a>
          <form method="POST" action="{{ route('logout') }}" class="mt-1">
            @csrf
            <button class="w-full text-left px-3 py-2 rounded-lg hover:bg-red-50 text-sm text-red-700">Logout</button>
          </form>
        </div>
      </details>
      @endauth

      @guest
        <a href="{{ route('login') }}" class="text-sm px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50">Login</a>
        <a href="{{ route('register') }}" class="text-sm px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Daftar</a>
      @endguest
    </nav>
  </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
  @yield('content')
</main>
</body>
</html>
