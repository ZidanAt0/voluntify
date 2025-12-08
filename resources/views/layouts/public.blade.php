<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'VolunteerHub' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

{{-- Grid 3 baris: header | konten fleksibel | footer (sticky) --}}

<body class="min-h-dvh h-full grid grid-rows-[auto,1fr,auto] bg-gray-50 text-gray-900">
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-xl font-semibold tracking-tight">Voluntify</a>
            <nav class="flex items-center gap-6 text-sm">
                <a href="#" class="hover:text-indigo-600">Explore</a>
                <a href="#" class="hover:text-indigo-600">About</a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-3 py-1.5 rounded-lg border border-gray-200 hover:border-indigo-500">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-3 py-1.5 rounded-lg border border-gray-200 hover:border-red-400">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-indigo-600">Login</a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Baris tengah akan meng-expand menutup sisa tinggi layar --}}
    <main class="px-4 sm:px-6">
        @yield('content')
    </main>

    <footer class="bg-white border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 text-sm text-gray-500">
            © {{ date('Y') }} VolunteerHub. All rights reserved.
        </div>
    </footer>
</body>

</html>
