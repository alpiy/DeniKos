@extends('app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">
        {{ isset($edit) && $edit ? 'Edit Data Kos' : 'Tambah Data Kos' }}
    </h1>

    <form action="#" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-md space-y-6">
        @csrf

        <div>
            <label class="block font-semibold mb-1" for="nama">Nama Kos</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $kos->nama ?? '') }}" class="w-full px-4 py-2 border rounded-lg" placeholder="Contoh: Kos Deni Putra">
        </div>

        <div>
            <label class="block font-semibold mb-1" for="alamat">Alamat</label>
            <textarea id="alamat" name="alamat" class="w-full px-4 py-2 border rounded-lg" rows="3">{{ old('alamat', $kos->alamat ?? '') }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1" for="harga">Harga per Bulan (Rp)</label>
            <input type="number" id="harga" name="harga" value="{{ old('harga', $kos->harga ?? '') }}" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div>
            <label class="block font-semibold mb-1" for="fasilitas">Fasilitas</label>
            <textarea id="fasilitas" name="fasilitas" class="w-full px-4 py-2 border rounded-lg" rows="3">{{ old('fasilitas', $kos->fasilitas ?? '') }}</textarea>
            <p class="text-sm text-gray-500">Pisahkan dengan koma (misal: WiFi, Kamar Mandi Dalam, Dapur)</p>
        </div>

        <div>
            <label class="block font-semibold mb-1" for="foto">Foto Kos</label>
            <input type="file" id="foto" name="foto" class="w-full px-4 py-2 border rounded-lg">
            @if(isset($kos) && $kos->foto)
                <img src="{{ asset('storage/' . $kos->foto) }}" alt="Foto Kos" class="w-40 mt-2 rounded">
            @endif
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
            {{ isset($edit) && $edit ? 'Simpan Perubahan' : 'Tambah Kos' }}
        </button>
    </form>
@endsection
