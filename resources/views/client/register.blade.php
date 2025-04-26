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

                <!-- Password -->
                <div class="relative">
                    <label for="password" class="block text-sm font-semibold">Password</label>
                    <input type="password" name="password" id="password" class="w-full border rounded-lg px-4 py-2" required>
                    <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 toggle-password" data-target="password" data-icon="eye-icon">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-gray-600">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3zM2.928 4.928C4.529 3.327 6.774 2.5 9 2.5c2.226 0 4.471.827 6.072 2.428l1.536 1.536c.593.593.593 1.554 0 2.121l-1.536 1.536c-1.601 1.601-3.846 2.428-6.072 2.428s-4.471-.827-6.072-2.428L2.928 7.585C2.335 7.001 2.335 5.941 2.928 5.348z"/>
                        </svg>
                    </button>
                    @error('password')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="relative">
                    <label for="password_confirmation" class="block text-sm font-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border rounded-lg px-4 py-2" required>
                    <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 toggle-password" data-target="password_confirmation" data-icon="eye-icon-confirm">
                        <svg id="eye-icon-confirm" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-gray-600">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3zM2.928 4.928C4.529 3.327 6.774 2.5 9 2.5c2.226 0 4.471.827 6.072 2.428l1.536 1.536c.593.593.593 1.554 0 2.121l-1.536 1.536c-1.601 1.601-3.846 2.428-6.072 2.428s-4.471-.827-6.072-2.428L2.928 7.585C2.335 7.001 2.335 5.941 2.928 5.348z"/>
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
