<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'DeniKos'))</title>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Tambahan styles dari setiap halaman jika diperlukan --}}
    @stack('styles')
</head>
<body class="antialiased">
    {{-- Konten Halaman akan dimuat di sini --}}
    @yield('content')

    {{-- Tambahan scripts dari setiap halaman jika diperlukan --}}
    @stack('scripts')
    
    <script>
        // Check if logged in user is admin trying to access user auth pages
        @auth
            @if(Auth::user()->role === 'admin')
                // Admin shouldn't be here, redirect to admin dashboard immediately
                console.log('Admin detected on user auth page, redirecting...');
                window.location.replace('{{ route("admin.dashboard") }}');
            @endif
        @endauth
    </script>
</body>
</html>