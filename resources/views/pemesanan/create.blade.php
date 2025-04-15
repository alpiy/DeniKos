@extends('app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Form Pemesanan Kos</h1>

    <form action="#" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-md space-y-6">
        @csrf

        <div>
            <label class="block font-semibold mb-1" for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" class="w-full px-4 py-2 border rounded-lg" placeholder="Masukkan nama Anda">
        </div>

        <div>
            <label class="block font-semibold mb-1" for="email">Email</label>
            <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg" placeholder="contoh@email.com">
        </div>

        <div>
            <label class="block font-semibold mb-1" for="no_hp">No. HP</label>
            <input type="text" id="no_hp" name="no_hp" class="w-full px-4 py-2 border rounded-lg" placeholder="08xxxxxxxxxx">
        </div>

        <div>
            <label class="block font-semibold mb-1" for="tgl_masuk">Tanggal Masuk</label>
            <input type="date" id="tgl_masuk" name="tgl_masuk" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div>
            <label class="block font-semibold mb-1" for="bukti_pembayaran">Upload Bukti Transfer</label>
            <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
            Kirim Pemesanan
        </button>
    </form>
@endsection
