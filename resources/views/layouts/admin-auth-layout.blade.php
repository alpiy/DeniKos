<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin DeniKos')</title>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Tambahan styles dari setiap halaman jika diperlukan --}}
    @stack('styles')
    <style>
        body {
            font-family: 'Inter', sans-serif; /* Contoh penggunaan font yang lebih modern */
        }
        .auth-bg {
            background-color: #f0f4f8; /* Warna latar belakang yang lebih netral */
            /* Atau gunakan gradien jika suka:
            background-image: linear-gradient(to top right, #6366f1, #8b5cf6);
            */
        }
    </style>
</head>
<body class="antialiased auth-bg">
    {{-- Konten Halaman akan dimuat di sini --}}
    @yield('content')

    {{-- Tambahan scripts dari setiap halaman jika diperlukan --}}
    @stack('scripts')
    
    <script>
        // Prevent caching for auth pages
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        @if(session('loggedOut'))
            // Clear all history after logout
            window.history.pushState(null, null, window.location.href);
            window.addEventListener('popstate', function(event) {
                window.history.pushState(null, null, window.location.href);
            });
        @endif
    </script>
</body>
</html>