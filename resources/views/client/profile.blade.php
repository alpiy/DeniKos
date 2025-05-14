@extends('app')

@section('content')
    <h1 class="text-2xl font-bold mb-8 text-center">Profil Saya</h1>

    <div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <div class="flex flex-col items-center mb-6">
            <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center mb-2">
                <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h2>
            <span class="text-sm text-gray-500">{{ $user->email }}</span>
        </div>
        <table class="w-full text-sm text-left text-gray-700">
            <tbody>
                <tr>
                    <td class="py-2 font-semibold w-1/3">Alamat</td>
                    <td class="py-2">{{ $user->alamat }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold">No. HP</td>
                    <td class="py-2">{{ $user->no_hp }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold">Jenis Kelamin</td>
                    <td class="py-2">{{ $user->jenis_kelamin }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold">Tanggal Bergabung</td>
                    <td class="py-2">{{ $user->created_at->format('d M Y') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection