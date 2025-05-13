@extends('client.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow">
        <h2 class="text-2xl font-bold text-center">Admin Login</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.auth.login.post') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" required autofocus>
                </div>
                
                <div>
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">
                        Login
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection