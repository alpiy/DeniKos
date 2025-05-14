@extends('client.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow">
        <div class="flex flex-col items-center">
            <div class="bg-blue-100 rounded-full p-3 mb-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-center text-blue-700 mb-2">Admin Login</h2>
        </div>
        
        @if (session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.auth.login.post') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="admin@example.com">
                @error('email')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="relative">
    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
    <input id="password" type="password" name="password" required
        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
        placeholder="********">
    <button type="button"
        data-toggle="password"
        data-target="password"
        class="absolute right-3 top-8 text-gray-500 hover:text-blue-600 focus:outline-none"
        tabindex="-1">
        <svg class="h-5 w-5 icon-show" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        <svg class="h-5 w-5 icon-hide hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.25-2.6A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
        </svg>
    </button>
    @error('password')
        <span class="text-xs text-red-600">{{ $message }}</span>
    @enderror
</div>
            
            <div class="flex items-center justify-between">
                <a href="#" class="text-sm text-blue-600 hover:underline">Lupa password?</a>
            </div>
            
            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 transition text-white p-2 rounded font-semibold shadow focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    Login
                </button>
            </div>
            <div class="mt-4 text-center">
    <a href="{{ route('auth.login.form') }}" class="text-blue-600 hover:underline text-sm">Login sebagai User</a>
</div>
        </form>
    </div>
</div>
<!-- Alpine.js for show/hide password -->

@endsection