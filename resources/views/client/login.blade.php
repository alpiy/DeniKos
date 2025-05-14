@extends('client.layout')

@section('title', 'Login')

@section('content')

    <div class="max-w-md mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

        <form action="{{ route('auth.login') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-2" required>
                    @error('email')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="relative">
                    <label for="password" class="block text-sm font-semibold">Password</label>
                    <input type="password" name="password" id="password" class="w-full border rounded-lg px-4 py-2" required>
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
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                        Login
                    </button>
                </div>
                <div class="mt-2 text-right">
    <a href="{{ route('auth.password.request') }}" class="text-blue-600 hover:underline text-sm">Lupa password?</a>
</div>

                <div class="mt-4 text-center">
                    Don't have an account? <a href="{{ route('auth.register.form') }}" class="text-blue-600 hover:underline">Register here</a>
                </div>
                <div class="mt-4 text-center">
    <a href="{{ route('admin.auth.login') }}" class="text-blue-600 hover:underline text-sm">Login sebagai Admin</a>
</div>
            </div>
        </form>
    </div>
@endsection
