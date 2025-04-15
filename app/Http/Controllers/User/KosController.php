<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KosController extends Controller
{
    public function index()
{
    return view('kos.index');
}
}
