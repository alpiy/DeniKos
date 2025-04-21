<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class KosController extends Controller
{
    public function index()
    {
        $data = Kos::all();
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
            'nama_kos' => 'required',
            'alamat' => 'required',
            'harga' => 'required|integer',
            'deskripsi' => 'required',
            'fasilitas' => 'required|array',
            'foto.*' => 'nullable|image|max:2048',
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
            $fotoPaths[] = $file->store('kos', 'public');
         }
        }

        Kos::create([
            'nama_kos' => $request->nama_kos,
            'alamat' => $request->alamat,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => $request->fasilitas,
            'foto' => $fotoPaths,
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
            'nama_kos' => 'required',
            'alamat' => 'required',
            'harga' => 'required|integer',
            'deskripsi' => 'required',
            'fasilitas' => 'required|array',
            'foto.*' => 'nullable|image|max:2048',
        ]);

        $fotoPaths = $ko->foto ?? [];

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotoPaths[] = $file->store('kos', 'public');
            }
        }

        $ko->update([
            'nama_kos' => $request->nama_kos,
            'alamat' => $request->alamat,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => $request->fasilitas,
            'foto' => $fotoPaths,
        ]);

        return redirect()->route('admin.kos.index')->with('success', 'Kos berhasil diperbarui!');
    }

    public function destroy(Kos $ko)
    {
         // Jika ada foto, hapus dari storage
        if ($ko->foto) {
        Storage::disk('public')->delete($ko->foto);
     }

        $ko->delete();
        return redirect()->route('admin.kos.index')->with('success', 'Kos berhasil dihapus.');
    }
}
