@extends('app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Profil Saya</h1>

    <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
        <p><strong>Nama :</strong> {{ $user->name }}</p>
        <p><strong>Email    :</strong> {{ $user->email }}</p>
        <p><strong>Alamat   :</strong> {{ $user->alamat }}</p>
        <p><strong>No.HP    :</strong> {{ $user->no_hp }}</p>
        <p><strong>Jenis Kelamin    :</strong> {{ $user->jenis_kelamin }}</p>
        <p><strong>Tanggal Bergabung    :</strong> {{ $user->created_at->format('d M Y') }}</p>
    </div>
@endsection
