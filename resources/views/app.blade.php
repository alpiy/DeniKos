<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'DeniKos' }}</title>
    @vite('resources/css/app.css') {{-- Tailwind CSS --}}
</head>
<body class="bg-gray-100 text-gray-800">

    {{-- Navbar --}}
    @include('base')

    {{-- Konten Halaman --}}
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>
</html>
