@csrf

<div class="space-y-4">
    {{-- <!-- Nama Kos -->
    <div>
        <label class="block font-semibold mb-1">Nama Kos</label>
        <input type="text" name="nama_kos" value="{{ old('nama_kos', $kos->nama_kos ?? '') }}" class="w-full border rounded-lg px-4 py-2">
    </div> --}}

    <!-- Alamat -->
    <div>
        <label class="block font-semibold mb-1">Alamat</label>
        <textarea name="alamat" class="w-full border rounded-lg px-4 py-2">{{ old('alamat', $kos->alamat ?? '') }}</textarea>
    </div>

    <!-- Harga Bulanan -->
    <div>
        <label class="block font-semibold mb-1">Harga Bulanan</label>
        <input type="number" name="harga_bulanan" value="{{ old('harga_bulanan', $kos->harga_bulanan ?? '') }}" class="w-full border rounded-lg px-4 py-2">
    </div>

    <!-- Deskripsi -->
    <div>
        <label class="block font-semibold mb-1">Deskripsi</label>
        <textarea name="deskripsi" class="w-full border rounded-lg px-4 py-2">{{ old('deskripsi', $kos->deskripsi ?? '') }}</textarea>
    </div>

    <!-- Fasilitas -->
    <div>
        <label class="block font-semibold mb-1">Fasilitas (pisahkan dengan koma)</label>
        <input type="text" name="fasilitas"
        value="{{ is_array(old('fasilitas')) ? implode(', ', old('fasilitas')) : (isset($kos) ? implode(', ', $kos->fasilitas ?? []) : '') }}"
        class="w-full border rounded-lg px-4 py-2">
    
    </div>

    <!-- Lantai -->
    <div>
        <label class="block font-semibold mb-1">Lantai</label>
        <select name="lantai" class="w-full border rounded-lg px-4 py-2">
            <option value="2" {{ old('lantai', $kos->lantai ?? '') == 2 ? 'selected' : '' }}>Lantai 2</option>
            <option value="3" {{ old('lantai', $kos->lantai ?? '') == 3 ? 'selected' : '' }}>Lantai 3</option>
        </select>
    </div>

    <!-- Nomor Kamar -->
    <div>
        <label class="block font-semibold mb-1">Nomor Kamar</label>
        <input type="text" name="nomor_kamar" value="{{ old('nomor_kamar', $kos->nomor_kamar ?? '') }}" class="w-full border rounded-lg px-4 py-2">
    </div>

    <!-- Status Kamar -->
    <div>
        <label class="block font-semibold mb-1">Status Kamar</label>
        <select name="status_kamar" class="w-full border rounded-lg px-4 py-2">
            <option value="tersedia" {{ old('status_kamar', $kos->status_kamar ?? 'tersedia') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="terpesan" {{ old('status_kamar', $kos->status_kamar ?? 'tersedia') == 'terpesan' ? 'selected' : '' }}>Terpesan</option>
        </select>
    </div>

    <!-- Foto -->
    <div>
        <label class="block font-semibold mb-1">Foto (bisa lebih dari satu)</label>
        <input type="file" name="foto[]" multiple class="w-full border rounded-lg px-4 py-2">

        {{-- Tampilkan preview foto jika mode edit --}}
        @if (isset($kos) && is_array($kos->foto))
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach ($kos->foto as $path)
                    <img src="{{ asset('storage/' . $path) }}" alt="Foto Kos" class="w-24 h-24 object-cover rounded shadow">
                @endforeach
            </div>
        @endif
    </div>

    <!-- Denah Kamar -->
    <div>
        <label class="block font-semibold mb-1">Denah Kamar</label>
        <input type="file" name="denah_kamar" class="w-full border rounded-lg px-4 py-2">
        {{-- Tampilkan preview denah jika mode edit --}}
        @if (isset($kos) && $kos->denah_kamar)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $kos->denah_kamar) }}" alt="Denah Kamar" class="w-24 h-24 object-cover rounded shadow">
            </div>
        @endif
    </div>

    <!-- Submit Button -->
    <div>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
            {{ isset($kos) ? 'Update Kos' : 'Simpan Kos' }}
        </button>
    </div>
</div>
