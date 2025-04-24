<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $backgroundFotos = [
            'images/landing/kos-slide1.jpeg',
            'images/landing/kos-slide2.jpeg',
            'images/landing/kos-slide3.jpeg',
        ];
        return view('landing',compact('backgroundFotos')); // Sesuaikan dengan nama file view landing page kamu
    }

}
