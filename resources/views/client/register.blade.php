@extends('client.layout')

@section('title', 'Register')

@section('content')
    <div class="max-w-md mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

        <form action="{{ route('auth.register') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full border rounded-lg px-4 py-2" required>
                    @error('name')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-2" required>
                    @error('email')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <!-- No HP -->
                <div>
                    <label for="no_hp" class="block text-sm font-semibold">No. HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" class="w-full border rounded-lg px-4 py-2" required>
                    @error('no_hp')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-sm font-semibold">Alamat</label>
                    <textarea name="alamat" id="alamat" class="w-full border rounded-lg px-4 py-2" required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <!-- Password -->
<!-- Password -->
<div class="relative">
    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
    <input type="password" name="password" id="password" class="w-full border rounded-lg px-4 py-2 pr-12" required>
    <button type="button"
        data-toggle="password"
        data-target="password"
        class="absolute right-3 top-8 text-gray-500 hover:text-blue-600 focus:outline-none"
        tabindex="-1">
        <svg class="w-6 h-6 icon-show" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        <svg class="w-6 h-6 icon-hide hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.25-2.6A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
        </svg>
    </button>
    @error('password')
        <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
</div>

<!-- Confirm Password -->
<div class="relative">
    <label for="password_confirmation" class="block text-sm font-semibold">Confirm Password</label>
    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border rounded-lg px-4 py-2 pr-12" required>
    <button type="button"
        data-toggle="password"
        data-target="password_confirmation"
         class="absolute right-3 top-7 text-gray-500 hover:text-blue-600 focus:outline-none"
        tabindex="-1">
        <svg class="w-6 h-6 icon-show" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        <svg class="w-6 h-6 icon-hide hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.25-2.6A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
        </svg>
    </button>
</div>

                <div>
                    <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                        Register
                    </button>
                </div>

                <div class="mt-4 text-center">
                    Already have an account? <a href="{{ route('auth.login.form') }}" class="text-blue-600 hover:underline">Login here</a>
                </div>
            </div>
        </form>
    </div>
@endsection
