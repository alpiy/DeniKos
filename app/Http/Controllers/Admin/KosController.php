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
        $data = $query->orderBy('nomor_kamar')->get();
        return view('admin.kos.index', compact('data'));
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
            'denah_kamar' => 'nullable|image|max:2048', // Jika ada denah kamar
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
            $fotoPaths[] = $file->store('kos', 'public');
         }
        }
         // Menyimpan denah kamar
         $denahPath = $request->hasFile('denah_kamar') ? $request->file('denah_kamar')->store('denah', 'public') : null;

        Kos::create([
            'alamat' => $request->alamat,
            'harga_bulanan' => $request->harga_bulanan,
            'lantai' => $request->lantai,
            'nomor_kamar' => $request->nomor_kamar,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => $request->fasilitas,
            'foto' => $fotoPaths,
            'denah_kamar' => $denahPath,
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
            'denah_kamar' => 'nullable|image|max:2048', // Jika ada denah kamar
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
        // Hapus denah jika dicentang
        $denahPath = $ko->denah_kamar;
        if ($request->filled('hapus_denah')) {
            if ($denahPath) {
                Storage::disk('public')->delete($denahPath);
            }
            $denahPath = null;
        }
        // Tambah foto baru
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotoPaths[] = $file->store('kos', 'public');
            }
        }
        // Tambah/replace denah baru
        if ($request->hasFile('denah_kamar')) {
            $denahPath = $request->file('denah_kamar')->store('denah', 'public');
        }

        $ko->update([
            'alamat' => $request->alamat,
            'harga_bulanan' => $request->harga_bulanan,
            'lantai' => $request->lantai,
            'nomor_kamar' => $request->nomor_kamar,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => $request->fasilitas,
            'foto' => $fotoPaths,
            'denah_kamar' => $denahPath,
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
        // Hapus denah kamar jika ada
        if (!empty($ko->denah_kamar)) {
            Storage::disk('public')->delete($ko->denah_kamar);
        }
        $ko->delete();
        return redirect()->route('admin.kos.index')->with('success', 'Kos berhasil dihapus.');
    }

    public function show(Kos $ko)
    {
        return view('admin.kos.show', ['kos' => $ko]);
    }
}
