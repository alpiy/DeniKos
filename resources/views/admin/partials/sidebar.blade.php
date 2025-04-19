<aside class="w-64 bg-white shadow-md">
    <div class="p-4 font-bold text-xl text-indigo-600 border-b">
        DeniKos Admin
    </div>
    <nav class="p-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-indigo-100">Dashboard</a>
        <a href="{{ route('admin.kos.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-100">Manajemen Kos</a>
        <a href="{{ route('admin.pemesanan.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-100">Daftar Pemesanan</a>
    </nav>
</aside>
