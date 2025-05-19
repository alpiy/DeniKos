<aside class="h-full flex flex-col">
    <div class="p-4 font-bold text-xl text-indigo-600 border-b">
        DeniKos Admin
    </div>
    <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-indigo-100">Dashboard</a>
        <a href="{{ route('admin.kos.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-100">Manajemen Kos</a>
        <a href="{{ route('admin.pemesanan.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-100">Daftar Pemesanan</a>
        <a href="{{ route('admin.pemesanan.perpanjang') }}" class="block px-4 py-2 rounded hover:bg-indigo-100">Perpanjangan Sewa</a>
        <a href="{{ route('admin.penyewa.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-100">Data Penyewa</a>
        <a href="{{ route('admin.laporan.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-100">Laporan Sewa</a>
    </nav>

    <div class="p-4 border-t">
        <form method="POST" action="{{ route('auth.logout') }}">
            @csrf
            <button type="submit" class="w-full px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">
                Logout
            </button>
        </form>
    </div>
</aside>
