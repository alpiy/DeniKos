@extends('layouts.auth-layout')

@section('title', 'Login ke DeniKos')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-700 p-4 sm:p-6 lg:p-8 selection:bg-yellow-400 selection:text-yellow-900">
    <div class="w-full max-w-md animate-fade-in-up" style="animation-duration: 0.5s;">
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-block">
                {{-- Ganti dengan SVG logo jika ada untuk kualitas lebih baik --}}
                <h1 class="text-5xl font-extrabold text-white tracking-tight" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">DeniKos</h1>
            </a>
            <p class="text-indigo-200 mt-2 text-lg">Masuk dan temukan kenyamanan tinggal.</p>
        </div>

        <div class="bg-white/95 backdrop-blur-md p-8 sm:p-10 rounded-xl shadow-2xl border border-white/20">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Selamat Datang Kembali</h2>

            @if (session('success'))
                <div class="mb-4 p-3 rounded-md bg-green-50 border border-green-300 text-green-700 text-sm animate-fade-in-down">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 rounded-md bg-red-50 border border-red-300 text-red-700 text-sm animate-fade-in-down">
                    {{ session('error') }}
                </div>
            @endif
            @error('email')
                <div class="mb-4 p-3 rounded-md bg-red-50 border border-red-300 text-red-700 text-sm animate-fade-in-down">
                    {{ $message }}
                    
                    {{-- Jika error terkait verifikasi email, tampilkan opsi resend --}}
                    @if(str_contains($message, 'verifikasi email') || str_contains($message, 'verify'))
                        <div class="mt-3 pt-3 border-t border-red-200">
                            <p class="text-xs text-red-600 mb-2">Belum menerima email verifikasi?</p>
                            <button type="button" onclick="showResendForm()" class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded transition">
                                Kirim Ulang Email Verifikasi
                            </button>
                        </div>
                    @endif
                </div>
            @enderror

            <form action="{{ route('auth.login') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400"
                                   required placeholder="email@anda.com">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-baseline">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                            <a href="{{ route('auth.password.request') }}" class="text-xs text-indigo-600 hover:text-indigo-500 hover:underline transition duration-150">Lupa password?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="password"
                                   class="w-full border-gray-300 rounded-lg shadow-sm pl-11 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400"
                                   required placeholder="••••••••">
                            <button type="button"
                                data-toggle="password"
                                data-target="password"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-indigo-600 focus:outline-none transition-colors duration-200"
                                tabindex="-1">
                                <svg class="w-5 h-5 icon-show transition-opacity duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="w-5 h-5 icon-hide hidden transition-opacity duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.25-2.6A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold px-6 py-3.5 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl active:scale-[0.98]">
                            Login ke Akun Saya
                        </button>
                    </div>

                    <div class="text-sm text-center text-gray-600 pt-2">
                        Belum punya akun? <a href="{{ route('auth.register.form') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline transition">Buat Akun Gratis</a>
                    </div>
                    <div class="relative flex py-2 items-center">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="flex-shrink mx-4 text-gray-400 text-xs">ATAU</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>
                    <div class="text-sm text-center">
                        <a href="{{ route('admin.auth.login') }}" class="text-gray-500 hover:text-indigo-600 hover:underline transition">Login sebagai Admin</a>
                    </div>
                </div>
            </form>

            {{-- Form Resend Email Verification (tersembunyi) --}}
            <div id="resendForm" class="hidden mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-sm font-semibold text-blue-800 mb-2">Kirim Ulang Email Verifikasi</h3>
                <form action="{{ route('auth.resend-verification') }}" method="POST">
                    @csrf
                    <div class="flex gap-2">
                        <input type="email" name="email" placeholder="Masukkan email Anda" 
                               class="flex-1 text-sm border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded transition">
                            Kirim
                        </button>
                    </div>
                </form>
                <button type="button" onclick="hideResendForm()" class="text-xs text-blue-600 hover:text-blue-500 mt-2">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Resend verification form functions
function showResendForm() {
    document.getElementById('resendForm').classList.remove('hidden');
}

function hideResendForm() {
    document.getElementById('resendForm').classList.add('hidden');
}
</script>
@endpush