<?php

namespace App\Http\Controllers\User;

use App\Models\Kos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KosController extends Controller
{
    public function index()
    {
        $dataKos = Kos::all(); // menampilkan semua kos
        return view('kos.index', compact('dataKos'));
    }

    public function show($id)
    {
        $kos = Kos::findOrFail($id); // ambil data kos berdasarkan id
        return view('kos.show', compact('kos'));
    }
}
