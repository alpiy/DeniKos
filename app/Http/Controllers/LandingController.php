<?php

namespace App\Http\Controllers;

use App\Models\LandingBackground;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        // Jika user sudah login dan merupakan admin, redirect ke dashboard admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        // Jika user biasa sudah login, mereka tetap bisa melihat landing page
        // untuk browsing kamar yang tersedia
        
        // Ambil background dari database, jika tidak ada gunakan default
        $backgroundFotos = LandingBackground::active()->ordered()->pluck('image_path')->toArray();
        
        // Jika tidak ada data di database, gunakan default images
        if (empty($backgroundFotos)) {
            $backgroundFotos = [
                'images/landing/kos-slide1.jpeg',
                'images/landing/Landing2.jpeg',
                'images/landing/landing3.jpeg',
            ];
        } else {
            // Tambahkan storage path untuk images dari database
            $backgroundFotos = array_map(function($path) {
                return 'storage/' . $path;
            }, $backgroundFotos);
        }
        
        return view('landing', compact('backgroundFotos'));
    }
}
