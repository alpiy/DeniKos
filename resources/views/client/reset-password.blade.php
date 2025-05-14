@extends('client.layout')
@section('title', 'Reset Password')
@section('content')
<div class="max-w-md mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">Reset Password</h2>
    <form method="POST" action="{{ route('auth.password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold">Email</label>
            <input type="email" name="email" id="email" class="w-full border rounded-lg px-4 py-2" required>
            @error('email')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold">Password Baru</label>
            <input type="password" name="password" id="password" class="w-full border rounded-lg px-4 py-2" required>
            @error('password')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-semibold">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border rounded-lg px-4 py-2" required>
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
            Reset Password
        </button>
    </form>
</div>
@endsection