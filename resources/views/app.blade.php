<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'DeniKos' }}</title>
     <script>
        window.Laravel = {
            userId: {{ Auth::check() ? Auth::id() : 'null' }}
        }
    </script>
    {{-- Swiper CSS --}}
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
     {{-- Tambahan styles dari setiap halaman --}}
     @yield('styles')
</head>
<body class="bg-gray-100 text-gray-800">

    {{-- Navbar --}}
    @include('base')

     {{-- Container Notifikasi Realtime User --}}
    <div id="user-realtime-notifikasi" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3"></div>

    {{-- Konten Halaman --}}
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>
   
     {{-- Tambahan scripts dari setiap halaman --}}
     @yield('scripts')
     @stack('scripts')
     @vite('resources/js/app.js')


</body>
</html>
