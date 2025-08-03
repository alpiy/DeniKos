@extends('admin.layout')

@section('title', 'Edit Background Landing')

@section('content')
<div class="space-y-8">
    <div class="flex items-center">
        <a href="{{ route('admin.landing-background.index') }}" class="text-indigo-600 hover:text-indigo-800 mr-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Background Landing</h1>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xl">
        <form action="{{ route('admin.landing-background.update', $landingBackground) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Current Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $landingBackground->image_path) }}" 
                             alt="Current background" 
                             class="max-h-48 rounded-lg shadow-sm">
                    </div>
                </div>

                <!-- Upload New Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Ganti Gambar (Opsional)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 transition-colors">
                        <input type="file" name="image" id="image" accept="image/*" class="hidden">
                        <div id="preview-container" class="hidden">
                            <img id="preview" class="max-h-48 mx-auto rounded-lg shadow-sm">
                            <button type="button" id="remove-preview" class="mt-2 text-sm text-red-600 hover:text-red-800">Hapus</button>
                        </div>
                        <div id="upload-placeholder">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-600 mb-1">Klik untuk upload gambar baru</p>
                            <p class="text-xs text-gray-400">JPEG, PNG, JPG (Max 2MB)</p>
                        </div>
                        <button type="button" onclick="document.getElementById('image').click()" class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            Pilih Gambar Baru
                        </button>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul (Opsional)</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $landingBackground->title) }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           placeholder="Misal: Slide Utama, Promo Spesial, dll.">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ $landingBackground->is_active ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Aktifkan background ini</span>
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.landing-background.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Background
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('remove-preview').addEventListener('click', function() {
    document.getElementById('image').value = '';
    document.getElementById('preview-container').classList.add('hidden');
    document.getElementById('upload-placeholder').classList.remove('hidden');
});
</script>
@endsection
