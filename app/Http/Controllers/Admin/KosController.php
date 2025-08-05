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
            $query->where('status_kamar', $request->input('status_kamar'));
        }
        if ($request->filled('q')) {
            $query->where('nomor_kamar', 'like', '%'.$request->input('q').'%');
        }
        if ($request->filled('lantai')) { // Tambahkan filter lantai jika diinginkan
        $query->where('lantai', $request->input('lantai'));
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
            'harga_bulanan' => 'required|integer|min:1',
            'lantai' => 'required|in:2,3', // hanya lantai 2 dan 3 yang tersedia
            'nomor_kamar' => 'required|integer|unique:kos|min:1|max:999', // Nomor kamar harus integer dan unik
            'deskripsi' => 'nullable',
            'fasilitas' => 'required|array',
            'foto' => 'nullable|array|max:5', // Maksimal 5 foto
            'foto.*' => 'nullable|image|max:2048', // Setiap foto max 2MB
        ], [
            'harga_bulanan.required' => 'Harga bulanan wajib diisi.',
            'harga_bulanan.integer' => 'Harga bulanan harus berupa angka.',
            'harga_bulanan.min' => 'Harga bulanan minimal Rp 1.',
            'lantai.required' => 'Lantai kamar wajib dipilih.',
            'lantai.in' => 'Lantai yang tersedia hanya lantai 2 dan 3.',
            'nomor_kamar.required' => 'Nomor kamar wajib diisi.',
            'nomor_kamar.integer' => 'Nomor kamar harus berupa angka.',
            'nomor_kamar.unique' => 'Nomor kamar sudah ada. Silakan gunakan nomor lain.',
            'nomor_kamar.min' => 'Nomor kamar minimal 1.',
            'nomor_kamar.max' => 'Nomor kamar maksimal 999.',
            'fasilitas.required' => 'Fasilitas kamar wajib diisi.',
            'fasilitas.array' => 'Format fasilitas tidak valid.',
            'foto.max' => 'Maksimal 5 foto yang dapat diupload.',
            'foto.*.image' => 'File yang diupload harus berupa gambar.',
            'foto.*.max' => 'Ukuran gambar maksimal 2MB per foto.',
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                // Pastikan tidak melebihi 5 foto
                if (count($fotoPaths) >= 5) {
                    break;
                }
                $fotoPaths[] = $file->store('kos', 'public');
            }
        }
        

        Kos::create([
            'alamat' => 'Jl. Laksda Adi Sucipto, Gg. 19, Pandanwangi, Blimbing, Kota Malang, Jawa Timur', // Alamat tetap DeniKos
            'harga_bulanan' => $request->input('harga_bulanan'),
            'lantai' => $request->input('lantai'),
            'nomor_kamar' => $request->input('nomor_kamar'),
            'deskripsi' => $request->input('deskripsi'),
            'fasilitas' => $request->input('fasilitas'),
            'foto' => $fotoPaths,
            'luas_kamar' => '2x3', // Luas tetap 2x3 meter
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
            'harga_bulanan' => 'required|integer|min:1',
            'lantai' => 'required|in:2,3', // hanya lantai 2 dan 3 yang tersedia
            'nomor_kamar' => 'required|integer|unique:kos,nomor_kamar,' . $ko->getKey() . '|min:1|max:999', // Nomor kamar harus integer dan unik kecuali untuk data yang sedang diedit
            'deskripsi' => 'nullable',
            'fasilitas' => 'required|array',
            'foto' => 'nullable|array|max:5', // Maksimal 5 foto total
            'foto.*' => 'nullable|image|max:2048',
        ], [
            'harga_bulanan.required' => 'Harga bulanan wajib diisi.',
            'harga_bulanan.integer' => 'Harga bulanan harus berupa angka.',
            'harga_bulanan.min' => 'Harga bulanan minimal Rp 1.',
            'lantai.required' => 'Lantai kamar wajib dipilih.',
            'lantai.in' => 'Lantai yang tersedia hanya lantai 2 dan 3.',
            'nomor_kamar.required' => 'Nomor kamar wajib diisi.',
            'nomor_kamar.integer' => 'Nomor kamar harus berupa angka.',
            'nomor_kamar.unique' => 'Nomor kamar sudah ada. Silakan gunakan nomor lain.',
            'nomor_kamar.min' => 'Nomor kamar minimal 1.',
            'nomor_kamar.max' => 'Nomor kamar maksimal 999.',
            'fasilitas.required' => 'Fasilitas kamar wajib diisi.',
            'fasilitas.array' => 'Format fasilitas tidak valid.',
            'foto.max' => 'Maksimal 5 foto yang dapat diupload.',
            'foto.*.image' => 'File yang diupload harus berupa gambar.',
            'foto.*.max' => 'Ukuran gambar maksimal 2MB per foto.',
        ]);

        $fotoPaths = $ko->foto ?? [];
        // Hapus foto yang dicentang
        if ($request->filled('hapus_foto')) {
            foreach ($request->input('hapus_foto') as $idx) {
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
                // Pastikan total foto tidak melebihi 5
                if (count($fotoPaths) >= 5) {
                    break; // Stop jika sudah mencapai maksimal
                }
                $fotoPaths[] = $file->store('kos', 'public');
            }
        }
      

        $ko->update([
            'alamat' => 'Jl. Contoh No. 123, DeniKos, Kota Anda', // Alamat tetap DeniKos
            'harga_bulanan' => $request->input('harga_bulanan'),
            'lantai' => $request->input('lantai'),
            'nomor_kamar' => $request->input('nomor_kamar'),
            'deskripsi' => $request->input('deskripsi'),
            'fasilitas' => $request->input('fasilitas'),
            'foto' => $fotoPaths,
            'luas_kamar' => '2x3', // Luas tetap 2x3 meter
            'status_kamar' => $request->input('status_kamar', $ko->status_kamar ?? 'tersedia'), // Memperbarui status jika ada input
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
