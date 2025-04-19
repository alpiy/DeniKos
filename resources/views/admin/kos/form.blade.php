@csrf

<div class="space-y-4">
    <div>
        <label class="block font-semibold mb-1">Nama Kos</label>
        <input type="text" name="nama_kos" value="{{ old('nama_kos', $kos->nama_kos ?? '') }}" class="w-full border rounded-lg px-4 py-2">
    </div>

    <div>
        <label class="block font-semibold mb-1">Alamat</label>
        <textarea name="alamat" class="w-full border rounded-lg px-4 py-2">{{ old('alamat', $kos->alamat ?? '') }}</textarea>
    </div>

    <div>
        <label class="block font-semibold mb-1">Harga</label>
        <input type="number" name="harga" value="{{ old('harga', $kos->harga ?? '') }}" class="w-full border rounded-lg px-4 py-2">
    </div>

    <div>
        <label class="block font-semibold mb-1">Deskripsi</label>
        <textarea name="deskripsi" class="w-full border rounded-lg px-4 py-2">{{ old('deskripsi', $kos->deskripsi ?? '') }}</textarea>
    </div>

    <div>
        <label class="block font-semibold mb-1">Fasilitas (pisahkan dengan koma)</label>
        <input type="text" name="fasilitas" 
       value="{{ is_array(old('fasilitas')) ? implode(', ', old('fasilitas')) : (isset($kos) ? implode(', ', $kos->fasilitas ?? []) : '') }}" 
       class="w-full border rounded-lg px-4 py-2">
    </div>

    <div>
        <label class="block font-semibold mb-1">Foto (opsional)</label>
        <input type="file" name="foto" class="w-full border rounded-lg px-4 py-2">
        @if (isset($kos) && $kos->foto)
            <img src="{{ asset('storage/' . $kos->foto) }}" alt="Foto Kos" class="w-32 mt-2 rounded">
        @endif
    </div>

    <div>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
            {{ isset($kos) ? 'Update' : 'Simpan' }}
        </button>
    </div>
</div>
