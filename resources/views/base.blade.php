<nav class=" sticky top-0 z-50 bg-white shadow-md">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <a href="/" class="text-xl font-bold text-indigo-600">DeniKos</a>
        <ul class="flex space-x-4">
            <li><a href="/" class="hover:text-indigo-600">Beranda</a></li>
            <li><a href="/kos" class="hover:text-indigo-600">Daftar Kos</a></li>
            @auth
                @if(auth()->user()->role === 'admin')
                    <li><a href="/dashboard" class="hover:text-indigo-600">Dashboard Admin</a></li>
                @else
                    <li><a href="/profile" class="hover:text-indigo-600">Akun Saya</a></li>
                @endif
                <li>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="hover:text-red-500">Logout</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('auth.login.form') }}" class="hover:text-indigo-600">Login</a></li>
                <li><a href="{{ route('auth.register.form') }}" class="hover:text-indigo-600">Register</a></li>
            @endauth
            {{-- <li><a href="#" class="hover:text-indigo-600">Login</a></li>
            <li><a href="#" class="hover:text-indigo-600">Register</a></li> --}}
        </ul>
    </div>
</nav>
