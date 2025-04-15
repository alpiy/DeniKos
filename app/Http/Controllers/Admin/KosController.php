<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KosController extends Controller
{
    public function index()
    {
        $kos = Kos::all();
        return view('admin.kos.index', compact('kos'));
    }

    public function create()
    {
        return view('admin.kos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'harga' => 'required|integer',
            'fasilitas' => 'required',
            'foto' => 'nullable|image',
        ]);

        $kos = new Kos();
        $kos->nama = $request->nama;
        $kos->alamat = $request->alamat;
        $kos->harga = $request->harga;
        $kos->fasilitas = $request->fasilitas;

        if ($request->hasFile('foto')) {
            $kos->foto = $request->file('foto')->store('foto_kos');
        }

        $kos->save();

        return redirect()->route('admin.kos.index');
    }
}
