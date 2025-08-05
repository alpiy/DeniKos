{{-- resources/views/admin/kos/form.blade.php --}}
@csrf

<div class="space-y-8 divide-y divide-gray-200">
    {{-- Bagian Informasi Dasar --}}
    <div>
        <div class="pt-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Dasar Kamar</h3>
            <p class="mt-1 text-sm text-gray-500">Detail umum mengenai kamar kos.</p>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="nomor_kamar" class="block text-sm font-medium text-gray-700">Nomor Kamar <span class="text-red-500">*</span></label>
                <input type="number" name="nomor_kamar" id="nomor_kamar" value="{{ old('nomor_kamar', $kos->nomor_kamar ?? '') }}" required min="1" max="999"
                       class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('nomor_kamar') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="Contoh: 101">
                <p class="mt-1 text-xs text-gray-500">Masukkan nomor kamar (angka saja, 1-999)</p>
                @error('nomor_kamar') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="lantai" class="block text-sm font-medium text-gray-700">Lantai <span class="text-red-500">*</span></label>
                <select id="lantai" name="lantai" required
                        class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('lantai') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="">Pilih Lantai</option>
                    <option value="2" {{ old('lantai', $kos->lantai ?? '') == 2 ? 'selected' : '' }}>Lantai 2</option>
                    <option value="3" {{ old('lantai', $kos->lantai ?? '') == 3 ? 'selected' : '' }}>Lantai 3</option>
                </select>
                @error('lantai') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="harga_bulanan" class="block text-sm font-medium text-gray-700">Harga Bulanan (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="harga_bulanan" id="harga_bulanan" value="{{ old('harga_bulanan', $kos->harga_bulanan ?? '') }}" required min="0"
                       class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('harga_bulanan') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="Contoh: 500000">
                @error('harga_bulanan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="status_kamar" class="block text-sm font-medium text-gray-700">Status Kamar <span class="text-red-500">*</span></label>
                <select id="status_kamar" name="status_kamar" required
                        class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('status_kamar') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="tersedia" {{ old('status_kamar', $kos->status_kamar ?? 'tersedia') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="terpesan" {{ old('status_kamar', $kos->status_kamar ?? 'tersedia') == 'terpesan' ? 'selected' : '' }}>Terpesan</option>
                </select>
                @error('status_kamar') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700">Luas Kamar</label>
                <div class="mt-1 p-3 bg-gray-50 rounded-md border border-gray-300">
                    <span class="text-sm text-gray-700 font-medium">2x3 meter</span>
                    <p class="text-xs text-gray-500 mt-1">Standar untuk semua kamar DeniKos</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian Deskripsi dan Fasilitas --}}
    <div class="pt-8">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Deskripsi & Fasilitas</h3>
            <p class="mt-1 text-sm text-gray-500">Informasi detail mengenai kondisi dan fasilitas kamar.</p>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-6">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Kamar</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                          class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('deskripsi') ? 'border-red-500' : 'border-gray-300' }}"
                          placeholder="Jelaskan detail kamar, suasana, dll.">{{ old('deskripsi', $kos->deskripsi ?? '') }}</textarea>
                @error('deskripsi') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="fasilitas" class="block text-sm font-medium text-gray-700">Fasilitas Kamar <span class="text-red-500">*</span></label>
                <input type="text" name="fasilitas" id="fasilitas"
                       value="{{ is_array(old('fasilitas', $kos->fasilitas ?? [])) ? implode(', ', old('fasilitas', $kos->fasilitas ?? [])) : (old('fasilitas', $kos->fasilitas ?? '')) }}"
                       required
                       class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('fasilitas') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="Contoh: WiFi, Kamar Mandi Dalam, AC, Lemari">
                <p class="mt-2 text-xs text-gray-500">Pisahkan setiap fasilitas dengan koma (,).</p>
                @error('fasilitas') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Bagian Media (Foto & Denah) --}}
    <div class="pt-8">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Media Kamar</h3>
            <p class="mt-1 text-sm text-gray-500">Unggah foto-foto kamar dan denah jika ada.</p>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            {{-- Foto Kamar --}}
            <div class="sm:col-span-6">
                <label for="foto" class="block text-sm font-medium text-gray-700">
                    Foto Kamar 
                    <span class="text-xs text-gray-500">(Maksimal 5 foto, masing-masing max 2MB)</span>
                </label>
                <div class="mt-1">
                    <input type="file" name="foto[]" id="foto" multiple accept="image/*" max="5"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-lg cursor-pointer hover:border-indigo-400 transition-colors {{ $errors->has('foto.*') ? 'border-red-500' : '' }}">
                    <p class="mt-1 text-xs text-gray-600">Format: JPG, PNG, GIF. Pilih beberapa foto sekaligus dengan Ctrl+Click (Windows) atau Cmd+Click (Mac)</p>
                </div>
                @error('foto.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                {{-- Foto yang Sudah Ada (Mode Edit) --}}
                @if (isset($kos) && is_array($kos->foto) && count($kos->foto) > 0)
                    <p class="mt-4 text-sm font-medium text-gray-700 mb-3">Foto Saat Ini:</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($kos->foto as $i => $path)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $path) }}" alt="Foto Kamar {{ $i + 1 }}" class="w-32 h-32 object-cover rounded-lg shadow-md border-2 border-gray-200">
                                <label class="absolute top-1 right-1 bg-white/95 hover:bg-red-100 p-1 rounded-full cursor-pointer transition-colors shadow-md">
                                    <input type="checkbox" name="hapus_foto[]" value="{{ $i }}" class="h-3 w-3 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                </label>
                                <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-white text-xs p-1 rounded-b-lg text-center">
                                    <span class="font-medium">Foto {{ $i + 1 }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Denah Kamar --}}
            
        </div>
    </div>
</div>

{{-- Tombol Aksi akan ditempatkan di create.blade.php dan edit.blade.php --}}