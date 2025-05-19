<nav class="sticky top-0 z-50 bg-white shadow-md">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <a href="/" class="text-2xl font-extrabold text-indigo-600 tracking-tight hover:text-indigo-800 transition">DeniKos</a>
        <ul class="flex space-x-2 md:space-x-6 items-center font-medium">
            <li>
                <a href="/" class="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition">
                    <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5 0h-2a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2v8a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden md:inline">Beranda</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.kos.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition">
                    <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m0-5V3a1 1 0 00-1-1H5a1 1 0 00-1 1v14a1 1 0 001 1h3" />
                    </svg>
                    <span class="hidden md:inline">Daftar Kamar</span>
                </a>
            </li>
            @auth
                @if(auth()->user()->role === 'admin')
                    <li>
                        <a href="/dashboard" class="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 8h2v-2H7v2zm0-4h2v-2H7v2zm0-4h2V7H7v2zm4 8h2v-2h-2v2zm0-4h2v-2h-2v2zm0-4h2V7h-2v2zm4 8h2v-2h-2v2zm0-4h2v-2h-2v2zm0-4h2V7h-2v2z" />
                            </svg>
                            <span class="hidden md:inline">Dashboard Admin</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('user.profile') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="hidden md:inline">Akun Saya</span>
                        </a>
                    </li>
                     <li>
        <a href="{{ route('user.riwayat') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition">
            <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
            </svg>
            <span class="hidden md:inline">Riwayat Pemesanan</span>
        </a>
    </li>
                @endif
                <li>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center px-3 py-2 rounded-lg hover:bg-red-50 hover:text-red-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
                            </svg>
                            <span class="hidden md:inline">Logout</span>
                        </button>
                    </form>
                </li>
            @else
                <li>
                    <a href="{{ route('auth.login.form') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7" />
                        </svg>
                        <span class="hidden md:inline">Login</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('auth.register.form') }}" class="flex items-center px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden md:inline">Register</span>
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</nav>