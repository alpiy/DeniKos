<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Verified;

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
            $user = Auth::user();
            
            // Jika admin mencoba login di halaman user
            if ($user->role === 'admin') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun admin harus login melalui halaman admin. <a href="' . route('admin.auth.login') . '" class="underline text-indigo-600 hover:text-indigo-800">Klik di sini untuk login admin</a>',
                ]);
            }
            
            // Cek apakah email user sudah diverifikasi (hanya untuk user biasa)
            if ($user->role === 'user' && is_null($user->email_verified_at)) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Silakan verifikasi email Anda terlebih dahulu. Cek kotak masuk email Anda.',
                ]);
            }

            $request->session()->regenerate();
        
            return redirect()->route('landing')->with('success', 'Selamat datang, ' . $user->name);
        }

 
         return back()->withErrors([
             'email' => 'Email atau password salah.',
         ]);
     }
 
     // Tampilkan halaman register
     public function showRegisterForm()
     {
         return view('client.register');
     }     // Proses register
     public function register(Request $request)
     {
         $validated = $request->validate([
             'name' => ['required', 'string', 'max:255'],
             'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
             'password' => ['required', 'confirmed', 'min:8'],
             'jenis_kelamin' => ['required', Rule::in(['Laki-laki'])], // Hanya laki-laki untuk kost putra
             'no_hp' => ['required', 'string', 'max:20', 'regex:/^08[0-9]{8,13}$/'],
             'alamat' => ['required', 'string', 'max:500'],
         ], [
             // Custom error messages
             'name.required' => 'Nama lengkap wajib diisi.',
             'name.string' => 'Nama harus berupa teks.',
             'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
             
             'email.required' => 'Email wajib diisi.',
             'email.email' => 'Format email tidak valid.',
             'email.unique' => 'Email sudah terdaftar. Gunakan email lain atau login jika sudah punya akun.',
             'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
             
             'password.required' => 'Password wajib diisi.',
             'password.min' => 'Password minimal 8 karakter.',
             'password.confirmed' => 'Konfirmasi password tidak sesuai.',
             
             'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
             'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki (DeniKos khusus kost putra).',
             
             'no_hp.required' => 'Nomor HP wajib diisi.',
             'no_hp.string' => 'Nomor HP harus berupa teks.',
             'no_hp.max' => 'Nomor HP tidak boleh lebih dari 20 karakter.',
             'no_hp.regex' => 'Nomor HP harus diawali dengan 08 dan berisi 10-15 digit (contoh: 081234567890).',
             
             'alamat.required' => 'Alamat wajib diisi.',
             'alamat.string' => 'Alamat harus berupa teks.',
             'alamat.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
         ]);

         $user = User::create([
             'name' => $validated['name'],
             'email' => $validated['email'],
             'password' => Hash::make($validated['password']),
             'no_hp' => $validated['no_hp'],
             'alamat' => $validated['alamat'],
             'jenis_kelamin' => 'Laki-laki', // Selalu laki-laki untuk kost putra
             'role' => 'user', // default user
         ]);

         // Kirim email verifikasi
         $user->sendEmailVerificationNotification();

         return redirect()->route('auth.login.form')->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun sebelum login.');
     }
 
     // Logout
     public function logout(Request $request)
     {
         Auth::logout();
 
         $request->session()->invalidate();
         $request->session()->regenerateToken();
 
         return redirect()->route('auth.login.form')->with('success', 'Anda telah berhasil logout.');
     }

     // Email Verification Methods
     public function emailVerificationNotice()
     {
         return view('client.verify-email');
     }

     public function emailVerificationVerify($id, $hash)
     {
         $user = User::findOrFail($id);

         // Cek apakah hash sesuai
         if (! hash_equals($hash, sha1($user->email))) {
             return redirect()->route('auth.login.form')->withErrors(['email' => 'Link verifikasi tidak valid atau sudah kedaluwarsa.']);
         }

         // Cek apakah sudah terverifikasi
         if (!is_null($user->email_verified_at)) {
             Auth::login($user);
             return redirect()->route('landing')->with('success', 'Email sudah terverifikasi sebelumnya.');
         }

         // Mark email sebagai verified
         $user->email_verified_at = now();
         $user->save();

         // Login user
         Auth::login($user);

         return redirect()->route('landing')->with('success', 'Email berhasil diverifikasi! Selamat datang di DeniKos.');
     }

     public function emailVerificationResend(Request $request)
     {
         if (!is_null($request->user()->email_verified_at)) {
             return redirect()->route('landing');
         }

         $request->user()->sendEmailVerificationNotification();

         return back()->with('success', 'Link verifikasi telah dikirim ulang ke email Anda.');
     }

     // Resend verification dari login page (tanpa perlu login)
     public function resendVerificationFromLogin(Request $request)
     {
         $request->validate([
             'email' => 'required|email|exists:users,email'
         ], [
             'email.exists' => 'Email tidak ditemukan dalam sistem kami.'
         ]);

         $user = User::where('email', $request->input('email'))->first();

         if (!is_null($user->email_verified_at)) {
             return back()->with('success', 'Email sudah terverifikasi. Silakan login.');
         }

         $user->sendEmailVerificationNotification();

         return back()->with('success', 'Link verifikasi telah dikirim ke email Anda. Silakan cek email dan klik link verifikasi.');
     }
}
