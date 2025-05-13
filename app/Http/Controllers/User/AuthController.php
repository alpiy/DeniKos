<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // Tampilkan halaman profile
    public function showProfile()
    {
        $user = Auth::user();
        return view('client.profile', compact('user'));
    }
     // Tampilkan halaman login
     public function showLoginForm()
     {
         return view('client.login');
     }
 
     // Proses login
     public function login(Request $request)
     {
         $credentials = $request->validate([
             'email' => ['required', 'email'],
             'password' => ['required'],
         ]);
 
        if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        return match(Auth::user()->role) {
            'user' => redirect()->route('landing')->with('success', 'Selamat datang, ' . Auth::user()->name),
            default => back()->withErrors(['email' => 'Akun admin harus login melalui halaman admin.']),
            // default => redirect()->route('landing'),
        };
    }

 
         return back()->withErrors([
             'email' => 'Email atau password salah.',
         ]);
     }
 
     // Tampilkan halaman register
     public function showRegisterForm()
     {
         return view('client.register');
     }
 
     // Proses register
     public function register(Request $request)
     {
         $validated = $request->validate([
             'name' => ['required', 'string', 'max:255'],
             'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
             'password' => ['required', 'confirmed', 'min:6'],
             'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
             'no_hp' => ['required', 'string', 'max:20'],
             'alamat' => ['required', 'string'],
         ]);
 
         $user = User::create([
             'name' => $validated['name'],
             'email' => $validated['email'],
             'password' => Hash::make($validated['password']),
             'no_hp' => $validated['no_hp'],
             'alamat' => $validated['alamat'],
             'jenis_kelamin' => $validated['jenis_kelamin'],
             'role' => 'user', // default user
         ]);
 
         Auth::login($user);
 
         return redirect()->route('landing')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name);
     }
 
     // Logout
     public function logout(Request $request)
     {
         Auth::logout();
 
         $request->session()->invalidate();
         $request->session()->regenerateToken();
 
         return redirect()->route('auth.login.form')->with('success', 'Anda telah berhasil logout.');
     }
}
