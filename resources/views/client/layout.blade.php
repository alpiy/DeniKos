<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | My Website</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-indigo-600 p-4">
        <div class="container mx-auto text-white">
            <a href="{{ route('landing') }}" class="text-lg font-bold">DeniKos</a>
        </div>
    </nav>

    {{-- Content --}}
    <div class="container mx-auto mt-6">
        @yield('content')
    </div>

</body>
</html>
