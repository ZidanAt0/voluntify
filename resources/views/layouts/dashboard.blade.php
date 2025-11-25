@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Dashboard' }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
<header class="bg-white border-b">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
    <a href="{{ url('/') }}" class="text-xl font-semibold">Voluntify</a>
    <nav class="flex items-center gap-3 text-sm">
      <span class="text-gray-600 hidden sm:inline">
        {{ Str::limit(auth()->user()->name, 24) }}
      </span>
      <form method="POST" action="{{ route('logout') }}" class="inline">
        @csrf
        <button type="submit"
          class="px-3 py-1.5 rounded-lg border border-gray-200 hover:border-red-400">
          Logout
        </button>
      </form>
    </nav>
  </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
  @yield('content')
</main>
</body>
</html>
