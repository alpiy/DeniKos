@extends('app')

@section('content')
    <h1 class="text-xl font-bold mb-4">Form Pemesanan Kos</h1>

    <form action="{{ route('pemesanan.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="kos_id" value="{{ $kos->id }}">

        <div>
            <label for="tanggal_masuk" class="block">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="border p-2 rounded w-full" required>
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Pesan Sekarang
        </button>
    </form>
@endsection
