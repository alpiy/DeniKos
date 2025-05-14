@extends('client.layout')
@section('title', 'Lupa Password')
@section('content')
<div class="max-w-md mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">Lupa Password</h2>
    @if (session('status'))
        <div class="mb-4 text-green-600">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('auth.password.email') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold">Email</label>
            <input type="email" name="email" id="email" class="w-full border rounded-lg px-4 py-2" required>
            @error('email')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
            Kirim Link Reset
        </button>
    </form>
</div>
@endsection