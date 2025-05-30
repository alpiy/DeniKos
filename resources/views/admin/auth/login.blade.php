@extends('layouts.admin-auth-layout') {{-- Menggunakan layout baru --}}

@section('title', 'Admin Login - DeniKos')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-md animate-fade-in-up" style="animation-duration: 0.5s;">
        {{-- Branding Admin --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center bg-indigo-600 p-3 rounded-full mb-3 shadow-lg">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Admin Panel DeniKos</h1>
            <p class="text-gray-500 mt-1 text-sm">Silakan login untuk melanjutkan.</p>
        </div>

        {{-- Card Form Login --}}
        <div class="bg-white p-8 sm:p-10 rounded-xl shadow-2xl border border-gray-200">
            
            @if (session('success'))
                <div class="mb-5 p-3.5 rounded-md bg-green-50 border border-green-300 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-md bg-red-50 border border-red-300 text-red-700 text-sm">
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.auth.login.post') }}" class="space-y-6">
                @csrf
                {{-- Input Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full border-gray-300 rounded-lg shadow-sm pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400 {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                               placeholder="admin@denikos.com">
                    </div>
                </div>
                
                {{-- Input Password --}}
                <div>
                    <div class="flex justify-between items-baseline">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        {{-- <a href="#" class="text-xs text-indigo-600 hover:text-indigo-500 hover:underline transition duration-150">Lupa password?</a> --}}
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required
                               class="w-full border-gray-300 rounded-lg shadow-sm pl-11 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400 {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                               placeholder="••••••••">
                        <button type="button"
                            data-toggle="password"
                            data-target="password"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-indigo-600 focus:outline-none"
                            tabindex="-1">
                            <svg class="h-5 w-5 icon-show" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="h-5 w-5 icon-hide hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.25-2.6A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                {{-- Tombol Login --}}
                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-200 shadow-md hover:shadow-lg active:scale-[0.98]">
                        Login Admin
                    </button>
                </div>

                {{-- Link ke Login User --}}
                <div class="text-sm text-center pt-4">
                    <a href="{{ route('auth.login.form') }}" class="text-gray-500 hover:text-indigo-600 hover:underline transition">
                        Bukan Admin? Login sebagai User
                    </a>
                </div>
            </form>
        </div>
         <p class="text-center text-xs text-gray-500 mt-8">
            &copy; {{ date('Y') }} DeniKos. All rights reserved.
        </p>
    </div>
</div>
@endsection