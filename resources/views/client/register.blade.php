@extends('layouts.auth-layout')

@section('title', 'Register Akun DeniKos')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-700 p-4 sm:p-6 lg:p-8 selection:bg-yellow-400 selection:text-yellow-900">
    <div class="w-full max-w-lg animate-fade-in-up" style="animation-duration: 0.5s;">
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-block">
                <h1 class="text-5xl font-extrabold text-white tracking-tight" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">DeniKos</h1>
            </a>
            <p class="text-indigo-200 mt-2 text-lg">Bergabunglah dengan DeniKos hari ini!</p>
        </div>

        <div class="bg-white/95 backdrop-blur-md p-8 sm:p-10 rounded-xl shadow-2xl border border-white/20">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Daftar Akun Baru</h2>

            @if (session('success'))
                <div class="mb-4 p-3 rounded-md bg-green-50 border border-green-300 text-green-700 text-sm animate-fade-in-down">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-md bg-red-50 border border-red-300 text-red-700 text-sm animate-fade-in-down">
                    <p class="font-semibold mb-1.5 text-base">Oops! Periksa kembali isian Anda:</p>
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('auth.register') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    {{-- Nama Lengkap --}}
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400"
                               required placeholder="Nama Lengkap Anda">
                    </div>

                    {{-- Email --}}
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400"
                               required placeholder="email@anda.com">
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-1.5">No. HP</label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400"
                               required placeholder="08xxxxxxxxxx">
                    </div>
                    
                    {{-- Jenis Kelamin --}}
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin"
                                class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required>
                            <option value="">Pilih...</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                     {{-- Alamat --}}
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3"
                                  class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400"
                                  required placeholder="Alamat lengkap Anda">{{ old('alamat') }}</textarea>
                    </div>


                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative group">
                            <input type="password" name="password" id="password"
                                   class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2.5 pr-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400"
                                   required placeholder="Minimal 6 karakter">
                            <button type="button" data-toggle="password" data-target="password" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-indigo-600 focus:outline-none" tabindex="-1">
                                <svg class="w-5 h-5 icon-show" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg class="w-5 h-5 icon-hide hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.25-2.6A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" /></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password</label>
                        <div class="relative group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2.5 pr-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400"
                                   required placeholder="Ulangi password">
                            <button type="button" data-toggle="password" data-target="password_confirmation" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-indigo-600 focus:outline-none" tabindex="-1">
                                <svg class="w-5 h-5 icon-show" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg class="w-5 h-5 icon-hide hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.25-2.6A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="md:col-span-2 pt-2">
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold px-6 py-3.5 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl active:scale-[0.98]">
                            Daftarkan Akun Saya
                        </button>
                    </div>

                    <div class="md:col-span-2 text-sm text-center text-gray-600">
                        Sudah punya akun? <a href="{{ route('auth.login.form') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline transition">Login di sini</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection