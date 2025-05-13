<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'DeniKos' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
     {{-- Tambahan styles dari setiap halaman --}}
     @yield('styles')
</head>
<body class="bg-gray-100 text-gray-800">

    {{-- Navbar --}}
    @include('base')

    {{-- Konten Halaman --}}
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>
   
     {{-- Tambahan scripts dari setiap halaman --}}
     @yield('scripts')
     @vite('resources/js/app.js')


</body>
</html>
