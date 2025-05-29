@extends('layouts.auth-layout') {{-- Menggunakan layout khusus auth --}}

@section('title', 'Lupa Password DeniKos')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-700 p-4 sm:p-6 lg:p-8 selection:bg-yellow-400 selection:text-yellow-900">
    <div class="w-full max-w-md animate-fade-in-up" style="animation-duration: 0.5s;">
        {{-- Logo atau Nama Brand --}}
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-block">
                <h1 class="text-5xl font-extrabold text-white tracking-tight" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">DeniKos</h1>
            </a>
        </div>

        {{-- Card Form Lupa Password --}}
        <div class="bg-white/95 backdrop-blur-md p-8 sm:p-10 rounded-xl shadow-2xl border border-white/20">
            <h2 class="text-3xl font-bold mb-4 text-center text-gray-800">Lupa Password?</h2>
            <p class="text-center text-gray-600 text-sm mb-8">
                Jangan khawatir! Masukkan alamat email Anda yang terdaftar, dan kami akan mengirimkan instruksi untuk mereset password Anda.
            </p>

            {{-- Pesan Status dari Session --}}
            @if (session('status'))
                <div class="mb-6 p-3.5 rounded-md bg-green-50 border border-green-300 text-green-700 text-sm animate-fade-in-down">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Pesan Error Validasi untuk Email --}}
            @error('email')
                <div class="mb-6 p-3.5 rounded-md bg-red-50 border border-red-300 text-red-700 text-sm animate-fade-in-down">
                    {{ $message }}
                </div>
            @enderror

            <form method="POST" action="{{ route('auth.password.email') }}">
                @csrf
                <div class="space-y-6">
                    {{-- Input Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400"
                                   required placeholder="Masukkan email terdaftar Anda">
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div>
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold px-6 py-3.5 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl active:scale-[0.98]">
                            Kirim Link Reset Password
                        </button>
                    </div>
                </div>
            </form>

            {{-- Link Kembali ke Login --}}
            <div class="mt-8 text-center text-sm">
                <a href="{{ route('auth.login.form') }}" class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline transition-colors duration-150">
                    &larr; Kembali ke Halaman Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection