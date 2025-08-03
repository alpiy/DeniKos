<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingBackground;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingBackgroundController extends Controller
{
    public function index()
    {
        $backgrounds = LandingBackground::ordered()->get();
        return view('admin.landing-background.index', compact('backgrounds'));
    }

    public function create()
    {
        return view('admin.landing-background.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $imagePath = $request->file('image')->store('landing-backgrounds', 'public');

        LandingBackground::create([
            'image_path' => $imagePath,
            'title' => $request->input('title'),
            'is_active' => $request->has('is_active'),
            'sort_order' => LandingBackground::max('sort_order') + 1
        ]);

        return redirect()->route('admin.landing-background.index')
                        ->with('success', 'Background berhasil ditambahkan!');
    }

    public function edit(LandingBackground $landingBackground)
    {
        return view('admin.landing-background.edit', compact('landingBackground'));
    }

    public function update(Request $request, LandingBackground $landingBackground)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $data = [
            'title' => $request->input('title'),
            'is_active' => $request->has('is_active')
        ];

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($landingBackground->image_path) {
                Storage::disk('public')->delete($landingBackground->image_path);
            }
            
            $data['image_path'] = $request->file('image')->store('landing-backgrounds', 'public');
        }

        $landingBackground->update($data);

        return redirect()->route('admin.landing-background.index')
                        ->with('success', 'Background berhasil diupdate!');
    }

    public function destroy(LandingBackground $landingBackground)
    {
        // Hapus file gambar
        if ($landingBackground->image_path) {
            Storage::disk('public')->delete($landingBackground->image_path);
        }

        $landingBackground->delete();

        return redirect()->route('admin.landing-background.index')
                        ->with('success', 'Background berhasil dihapus!');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:landing_backgrounds,id',
            'items.*.sort_order' => 'required|integer'
        ]);

        foreach ($request->input('items') as $item) {
            LandingBackground::where('id', $item['id'])
                           ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
