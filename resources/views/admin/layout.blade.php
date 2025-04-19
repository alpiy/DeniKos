<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
