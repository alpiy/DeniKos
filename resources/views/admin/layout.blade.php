<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <div class="w-64 bg-white shadow-lg fixed top-0 bottom-0 z-30">
            @include('admin.partials.sidebar')
        </div>
          <div id="realtime-notifikasi" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3">
          
        </div>
        {{-- Konten --}}
        <div class="flex-1 ml-64 overflow-y-auto p-6">
            @yield('content')
        </div>
    </div>
     @vite(['resources/js/app.js', 'resources/js/echo.js'])
</body>
</html>
