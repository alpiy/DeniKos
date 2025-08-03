<aside class="h-full flex flex-col bg-gradient-to-b from-indigo-900 to-indigo-800 text-white shadow-xl">
    <!-- Header -->
    <div class="p-6 border-b border-indigo-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h1 class="font-bold text-xl">DeniKos</h1>
                <p class="text-indigo-300 text-sm">Admin Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto p-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-indigo-700 hover:translate-x-1 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 shadow-lg' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="{{ route('admin.kos.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-indigo-700 hover:translate-x-1 {{ request()->routeIs('admin.kos.*') ? 'bg-indigo-600 shadow-lg' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span class="font-medium">Manajemen Kos</span>
        </a>

        <a href="{{ route('admin.landing-background.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-indigo-700 hover:translate-x-1 {{ request()->routeIs('admin.landing-background.*') ? 'bg-indigo-600 shadow-lg' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="font-medium">Background Landing</span>
        </a>

        <a href="{{ route('admin.pemesanan.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-indigo-700 hover:translate-x-1 {{ request()->routeIs('admin.pemesanan.*') ? 'bg-indigo-600 shadow-lg' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span class="font-medium">Daftar Pemesanan</span>
        </a>

        {{-- <a href="{{ route('admin.pemesanan.perpanjang') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-indigo-700 hover:translate-x-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium">Perpanjangan Sewa</span>
        </a> --}}

        <a href="{{ route('admin.penyewa.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-indigo-700 hover:translate-x-1 {{ request()->routeIs('admin.penyewa.*') ? 'bg-indigo-600 shadow-lg' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
            </svg>
            <span class="font-medium">Data Penyewa</span>
        </a>

        <a href="{{ route('admin.laporan.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-indigo-700 hover:translate-x-1 {{ request()->routeIs('admin.laporan.*') ? 'bg-indigo-600 shadow-lg' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="font-medium">Histori & Laporan</span>
        </a>
    </nav>

    <!-- User Info & Logout -->
    <div class="p-4 border-t border-indigo-700">
        <div class="flex items-center space-x-3 mb-4 p-3 bg-indigo-800 rounded-lg">
            <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-medium text-sm">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-indigo-300 text-xs">Administrator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('auth.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all duration-200 hover:shadow-lg active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>
