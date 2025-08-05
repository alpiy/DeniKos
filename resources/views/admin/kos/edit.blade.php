@extends('admin.layout')

@section('title', 'Edit Kamar Kos: ' . $kos->nomor_kamar)

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Edit Kamar Kos: <span class="text-indigo-600">{{ $kos->nomor_kamar }}</span></h1>
            <p class="mt-1 text-sm text-gray-500">Perbarui detail informasi untuk kamar kos ini.</p>
        </div>
         <a href="{{ route('admin.kos.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar Kos
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-md shadow" role="alert">
             <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 5v6h2V5H9zm0 8h2v2H9v-2z"/></svg>
                </div>
                <div>
                    <p class="font-bold text-red-700">Terjadi Kesalahan</p>
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.kos.update', $kos->id) }}" method="POST" enctype="multipart/form-data"
          class="bg-white shadow-xl rounded-xl p-6 md:p-8">
        @method('PUT')
        @include('admin.kos.form', ['kos' => $kos]) {{-- Kirim $kos ke form include --}}

        <div class="pt-8 mt-8 border-t border-gray-200">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.kos.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Update Kamar Kos
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('foto');
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const fileCount = e.target.files.length;
            const helpText = fileInput.parentElement.querySelector('p');
            
            if (fileCount > 0) {
                helpText.innerHTML = `Format: JPG, PNG, GIF. <strong>${fileCount} foto dipilih</strong>`;
                helpText.className = 'mt-1 text-xs text-green-600 font-medium';
            } else {
                helpText.innerHTML = 'Format: JPG, PNG, GIF. Pilih beberapa foto sekaligus dengan Ctrl+Click (Windows) atau Cmd+Click (Mac)';
                helpText.className = 'mt-1 text-xs text-gray-600';
            }
        });
    }
});
</script>
@endpush