<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class KosController extends Controller
{
    public function index(Request $request)
    {
        $query = Kos::query();
        if ($request->filled('status_kamar')) {
            $query->where('status_kamar', $request->status_kamar);
        }
        if ($request->filled('q')) {
            $query->where('nomor_kamar', 'like', '%'.$request->q.'%');
        }
        if ($request->filled('lantai')) { // Tambahkan filter lantai jika diinginkan
        $query->where('lantai', $request->lantai);
        }
        // Sorting
        $sortableColumns = [
            'nomor_kamar' => 'nomor_kamar',
            'lantai' => 'lantai',
            'harga_bulanan' => 'harga_bulanan',
            'status_kamar' => 'status_kamar',
            // Tambahkan kolom lain yang ingin bisa di-sort
        ];

        $sortBy = $request->input('sort_by', 'nomor_kamar'); // Default sort
        $sortDirection = $request->input('sort_direction', 'asc'); // Default direction

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        if (array_key_exists($sortBy, $sortableColumns)) {
             // Jika nomor_kamar ingin di sort secara numerik, bukan alphabetical
            if ($sortBy == 'nomor_kamar') {
                 // Ini asumsi nomor kamar bisa berupa angka atau angka+huruf.
                 // Untuk sorting numerik murni, CAST(nomor_kamar AS UNSIGNED) lebih baik jika formatnya angka semua.
                $query->orderByRaw('LENGTH(nomor_kamar) ' . $sortDirection . ', nomor_kamar ' . $sortDirection);
            } else {
                $query->orderBy($sortableColumns[$sortBy], $sortDirection);
            }
        } else {
            $query->orderBy('nomor_kamar', 'asc'); // Fallback default sort
        }

        $dataKos = $query->paginate(15)->withQueryString(); 
        $totalKos = Kos::count();
        $tersediaCount = Kos::where('status_kamar', 'tersedia')->count();
        $terpesanCount = Kos::where('status_kamar', 'terpesan')->count();


        return view('admin.kos.index', compact('dataKos', 'totalKos', 'tersediaCount', 'terpesanCount','sortBy', 'sortDirection'));
    }

    public function create()
    {
        return view('admin.kos.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'fasilitas' => array_map('trim', explode(',', $request->input('fasilitas'))),
        ]);
        $request->validate([
           'alamat' => 'required',
            'harga_bulanan' => 'required|integer',
            'lantai' => 'required|in:2,3', // hanya lantai 2 dan 3 yang tersedia
            'nomor_kamar' => 'required|unique:kos', // Nomor kamar harus unik
            'deskripsi' => 'nullable',
            'fasilitas' => 'required|array',
            'foto.*' => 'nullable|image|max:2048',
            // Jika ada denah kamar
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
            $fotoPaths[] = $file->store('kos', 'public');
         }
        }
        

        Kos::create([
            'alamat' => $request->alamat,
            'harga_bulanan' => $request->harga_bulanan,
            'lantai' => $request->lantai,
            'nomor_kamar' => $request->nomor_kamar,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => $request->fasilitas,
            'foto' => $fotoPaths,

            'status_kamar' => 'tersedia', // Default status kamar adalah tersedia
        ]);

        return redirect()->route('admin.kos.index')->with('success', 'Kos berhasil ditambahkan!');
    }

    public function edit(Kos $ko)
    {
        return view('admin.kos.edit', ['kos' => $ko]);
    }

    public function update(Request $request, Kos $ko)
    {
        $request->merge([
            'fasilitas' => array_map('trim', explode(',', $request->input('fasilitas'))),
        ]);
        $request->validate([
            'alamat' => 'required',
            'harga_bulanan' => 'required|integer',
            'lantai' => 'required|in:2,3', // hanya lantai 2 dan 3 yang tersedia
            'nomor_kamar' => 'required|unique:kos,nomor_kamar,' . $ko->id, // Nomor kamar harus unik, kecuali untuk data yang sedang diedit
            'deskripsi' => 'nullable',
            'fasilitas' => 'required|array',
            'foto.*' => 'nullable|image|max:2048',
            // Jika ada denah kamar
        ]);

        $fotoPaths = $ko->foto ?? [];
        // Hapus foto yang dicentang
        if ($request->filled('hapus_foto')) {
            foreach ($request->hapus_foto as $idx) {
                if (isset($fotoPaths[$idx])) {
                    Storage::disk('public')->delete($fotoPaths[$idx]);
                    unset($fotoPaths[$idx]);
                }
            }
            $fotoPaths = array_values($fotoPaths); // reindex
        }
       
        // Tambah foto baru
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotoPaths[] = $file->store('kos', 'public');
            }
        }
      

        $ko->update([
            'alamat' => $request->alamat,
            'harga_bulanan' => $request->harga_bulanan,
            'lantai' => $request->lantai,
            'nomor_kamar' => $request->nomor_kamar,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => $request->fasilitas,
            'foto' => $fotoPaths,
           
            'status_kamar' => $request->status_kamar ?? $ko->status_kamar, // Memperbarui status jika ada input
        ]);

        return redirect()->route('admin.kos.index')->with('success', 'Kos berhasil diperbarui!');
    }

    public function destroy(Kos $ko)
    {
        // Jika ada foto, hapus dari storage
        if (is_array($ko->foto)) {
            foreach ($ko->foto as $foto) {
                if ($foto) {
                    Storage::disk('public')->delete($foto);
                }
            }
        }
      
        $ko->delete();
        return redirect()->route('admin.kos.index')->with('success', 'Kos berhasil dihapus.');
    }

    public function show(Kos $ko)
    {
        return view('admin.kos.show', ['kos' => $ko]);
    }
}
