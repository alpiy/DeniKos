@extends('layouts.auth-layout')

@section('title', 'Verifikasi Email - DeniKos')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-700 p-4 sm:p-6 lg:p-8 selection:bg-yellow-400 selection:text-yellow-900">
    <div class="w-full max-w-lg animate-fade-in-up" style="animation-duration: 0.5s;">
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-block">
                <h1 class="text-5xl font-extrabold text-white tracking-tight" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">DeniKos</h1>
            </a>
            <p class="text-indigo-200 mt-2 text-lg">Verifikasi Email Anda</p>
        </div>

        <div class="bg-white/95 backdrop-blur-md p-8 sm:p-10 rounded-xl shadow-2xl border border-white/20">
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-indigo-100 mb-4">
                    <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Verifikasi Email Anda</h2>
                <p class="text-gray-600 leading-relaxed">
                    Terima kasih telah mendaftar di DeniKos! Untuk keamanan akun Anda, silakan verifikasi alamat email dengan mengklik link yang telah dikirimkan ke:
                </p>
                <p class="font-semibold text-indigo-600 mt-2">{{ Auth::user()->email }}</p>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 rounded-md bg-green-50 border border-green-300 text-green-700 text-sm animate-fade-in-down">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-800 mb-2">📧 Cek Email Anda</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Periksa kotak masuk email Anda</li>
                        <li>• Jangan lupa cek folder Spam/Junk</li>
                        <li>• Klik link verifikasi di email</li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('auth.verification.send') }}" class="text-center">
                    @csrf
                    <p class="text-gray-600 text-sm mb-4">
                        Tidak menerima email verifikasi?
                    </p>
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl active:scale-[0.98]">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        Ingin menggunakan email lain? 
                        <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline transition">
                                Daftar Ulang
                            </button>
                        </form>
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <p class="text-indigo-200 text-sm">
                Dengan mendaftar, Anda menyetujui syarat dan ketentuan DeniKos
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto refresh halaman setelah 30 detik untuk cek verifikasi
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endpush
