@extends('layouts.auth-layout')

@section('title', 'Register Akun DeniKos')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-700 p-4 sm:p-6 lg:p-8 selection:bg-yellow-400 selection:text-yellow-900">
    <div class="w-full max-w-2xl animate-fade-in-up" style="animation-duration: 0.5s;">
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-block">
                <h1 class="text-5xl font-extrabold text-white tracking-tight" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">DeniKos</h1>
            </a>
            <p class="text-indigo-200 mt-2 text-lg">Bergabunglah dengan DeniKos hari ini!</p>
        </div>

        <div class="bg-white/95 backdrop-blur-md p-8 sm:p-10 rounded-xl shadow-2xl border border-white/20">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Daftar Akun Baru</h2>

            @if (session('success'))
                {{-- Success message handling --}}
            @endif
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-md bg-red-50 border border-red-300 text-red-700 text-sm animate-fade-in-down">
                    <p class="font-semibold mb-1.5 text-base">Oops! Periksa kembali isian Anda:</p>
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('auth.register') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    
                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="w-full rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}"
                               required placeholder="Nama Lengkap Anda">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="w-full rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}"
                               required placeholder="email@anda.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-1.5">No. HP</label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                               class="w-full rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400 {{ $errors->has('no_hp') ? 'border-red-500' : 'border-gray-300' }}"
                               required placeholder="08xxxxxxxxxx">
                        <p class="mt-1 text-xs text-gray-500">Contoh: 081234567890</p>
                        @error('no_hp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Jenis Kelamin --}}
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin"
                                class="w-full rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 bg-gray-100 {{ $errors->has('jenis_kelamin') ? 'border-red-500' : 'border-gray-300' }}" readonly required>
                            <option value="Laki-laki" selected>Laki-laki</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">DeniKos hanya melayani penyewa laki-laki</p>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative group">
                            <input type="password" name="password" id="password"
                                   class="w-full rounded-lg shadow-sm px-4 py-2.5 pr-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                                   required placeholder="Minimal 8 karakter">
                            {{-- Tombol Show/Hide Password --}}
                            <button type="button" data-toggle="password" data-target="password" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-indigo-600 focus:outline-none transition-colors duration-200" tabindex="-1">
                                <svg class="w-5 h-5 icon-show transition-opacity duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg class="w-5 h-5 icon-hide hidden transition-opacity duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.25-2.6A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" /></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password</label>
                        <div class="relative group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full rounded-lg shadow-sm px-4 py-2.5 pr-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400 {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }}"
                                   required placeholder="Ulangi password">
                            {{-- Tombol Show/Hide Password --}}
                            <button type="button" data-toggle="password" data-target="password_confirmation" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-indigo-600 focus:outline-none transition-colors duration-200" tabindex="-1">
                                <svg class="w-5 h-5 icon-show transition-opacity duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg class="w-5 h-5 icon-hide hidden transition-opacity duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.25-2.6A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" /></svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Real-time Password Feedback --}}
                    <div id="password-feedback" class="md:col-span-2 text-xs space-y-1"></div>


                    {{-- Alamat --}}
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3"
                                  class="w-full rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 placeholder-gray-400 {{ $errors->has('alamat') ? 'border-red-500' : 'border-gray-300' }}"
                                  required placeholder="Alamat lengkap Anda">{{ old('alamat') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Masukkan alamat lengkap tempat tinggal Anda</p>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="md:col-span-2 pt-2">
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold px-6 py-3.5 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl active:scale-[0.98]">
                            Daftarkan Akun Saya
                        </button>
                    </div>

                    {{-- Info Email Verification --}}
                    <div class="md:col-span-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">📧 Setelah Daftar:</p>
                                <ol class="list-decimal list-inside space-y-1 text-xs">
                                    <li>Email verifikasi akan dikirim otomatis</li>
                                    <li>Cek kotak masuk (dan folder Spam)</li>
                                    <li>Klik link verifikasi di email</li>
                                    <li>Login otomatis setelah verifikasi</li>
                                    <li>Jika tidak ada email, bisa kirim ulang di halaman login</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 text-sm text-center text-gray-600">
                        Sudah punya akun? <a href="{{ route('auth.login.form') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline transition">Login di sini</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation variables
    const form = document.querySelector('form');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const noHpInput = document.getElementById('no_hp');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const alamatInput = document.getElementById('alamat');
    const feedbackContainer = document.getElementById('password-feedback');
    
    // Real-time validation functions
    function validateName() {
        if (!nameInput) return true;
        const value = nameInput.value.trim();
        const parent = nameInput.closest('div');
        const errorElement = parent.querySelector('.text-red-600');
        
        if (errorElement) errorElement.remove();
        
        if (value.length === 0) {
            showFieldError(parent, 'Nama lengkap wajib diisi');
            return false;
        } else if (value.length > 255) {
            showFieldError(parent, 'Nama tidak boleh lebih dari 255 karakter');
            return false;
        }
        return true;
    }
    
    function validateEmail() {
        if (!emailInput) return true;
        const value = emailInput.value.trim();
        const parent = emailInput.closest('div');
        const errorElement = parent.querySelector('.text-red-600');
        
        if (errorElement) errorElement.remove();
        
        if (value.length === 0) {
            showFieldError(parent, 'Email wajib diisi');
            return false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            showFieldError(parent, 'Format email tidak valid');
            return false;
        }
        return true;
    }
    
    function validateNoHp() {
        if (!noHpInput) return true;
        const value = noHpInput.value.trim();
        const parent = noHpInput.closest('div');
        const errorElement = parent.querySelector('.text-red-600');
        
        if (errorElement) errorElement.remove();
        
        if (value.length === 0) {
            showFieldError(parent, 'Nomor HP wajib diisi');
            return false;
        } else if (!/^08[0-9]{8,13}$/.test(value)) {
            showFieldError(parent, 'Nomor HP harus diawali 08 dan berisi 10-15 digit');
            return false;
        }
        return true;
    }
    
    function validateAlamat() {
        if (!alamatInput) return true;
        const value = alamatInput.value.trim();
        const parent = alamatInput.closest('div');
        const errorElement = parent.querySelector('.text-red-600');
        
        if (errorElement) errorElement.remove();
        
        if (value.length === 0) {
            showFieldError(parent, 'Alamat wajib diisi');
            return false;
        } else if (value.length > 500) {
            showFieldError(parent, 'Alamat tidak boleh lebih dari 500 karakter');
            return false;
        }
        return true;
    }
    
    function showFieldError(parent, message) {
        const errorElement = document.createElement('p');
        errorElement.className = 'mt-1 text-sm text-red-600';
        errorElement.textContent = message;
        parent.appendChild(errorElement);
    }
    
    // Password strength validation
    function validatePassword() {
        if (!passwordInput || !feedbackContainer) return true;
        
        const value = passwordInput.value;
        feedbackContainer.innerHTML = ''; // Clear previous feedback

        if (value.length === 0) {
            return true; // Don't show feedback for empty field
        }

        const checks = [
            { condition: value.length >= 8, text: 'Minimal 8 karakter' },
            { condition: /[a-zA-Z]/.test(value), text: 'Mengandung huruf' },
            { condition: /\d/.test(value), text: 'Mengandung angka' }
        ];

        let allValid = true;
        checks.forEach(check => {
            const div = document.createElement('div');
            const icon = check.condition ? '✅' : '❌';
            const colorClass = check.condition ? 'text-green-600' : 'text-red-500';
            div.className = `flex items-center ${colorClass}`;
            div.innerHTML = `<span class="mr-2">${icon}</span> ${check.text}`;
            feedbackContainer.appendChild(div);
            if (!check.condition) {
                allValid = false;
            }
        });
        
        // Also validate confirmation if it has a value
        validatePasswordConfirm();
        return allValid;
    }
    
    function validatePasswordConfirm() {
        if (!passwordConfirmInput || !passwordInput || !feedbackContainer) return true;
        
        // Remove existing confirmation feedback
        const existingConfirmFeedback = document.getElementById('confirm-feedback');
        if(existingConfirmFeedback) existingConfirmFeedback.remove();

        const confirmValue = passwordConfirmInput.value;
        const passwordValue = passwordInput.value;
        
        if (confirmValue.length > 0) {
            const div = document.createElement('div');
            div.id = 'confirm-feedback';
            
            if (confirmValue === passwordValue && passwordValue.length > 0) {
                div.className = 'flex items-center text-green-600';
                div.innerHTML = `<span class="mr-2">✅</span> Password sesuai`;
                return true;
            } else {
                div.className = 'flex items-center text-red-500';
                div.innerHTML = `<span class="mr-2">❌</span> Password tidak sesuai`;
                feedbackContainer.appendChild(div);
                return false;
            }
            feedbackContainer.appendChild(div);
        }
        return true;
    }
    
    // Add event listeners
    if (nameInput) nameInput.addEventListener('blur', validateName);
    if (emailInput) emailInput.addEventListener('blur', validateEmail);
    if (noHpInput) noHpInput.addEventListener('blur', validateNoHp);
    if (alamatInput) alamatInput.addEventListener('blur', validateAlamat);
    if (passwordInput) passwordInput.addEventListener('input', validatePassword);
    if (passwordConfirmInput) passwordConfirmInput.addEventListener('input', validatePasswordConfirm);
    
    // Form submit validation
    if (form) {
        form.addEventListener('submit', function(e) {
            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isNoHpValid = validateNoHp();
            const isAlamatValid = validateAlamat();
            const isPasswordValid = validatePassword();
            const isPasswordConfirmValid = validatePasswordConfirm();
            
            if (!isNameValid || !isEmailValid || !isNoHpValid || !isAlamatValid || !isPasswordValid || !isPasswordConfirmValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.text-red-600');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
    
    // Show/Hide password functionality
    document.querySelectorAll('[data-toggle="password"]').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const iconShow = this.querySelector('.icon-show');
            const iconHide = this.querySelector('.icon-hide');
            
            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                iconShow.classList.add('hidden');
                iconHide.classList.remove('hidden');
            } else {
                targetInput.type = 'password';
                iconShow.classList.remove('hidden');
                iconHide.classList.add('hidden');
            }
        });
    });
});
</script>
@endpush