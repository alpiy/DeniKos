@extends('app')

@section('content')
    <!-- Compact Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-6 px-4 rounded-lg mb-6 shadow-lg">
        <h1 class="text-2xl font-bold text-center mb-1">🏠 DeniKos</h1>
        <p class="text-center text-blue-100 text-sm">Kamar modern dengan fasilitas lengkap</p>
    </div>

    <!-- Compact Error Notification -->
    @if (session('error'))
        <div id="notif-error" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded-lg shadow-lg mb-4 transition-opacity duration-500">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @php
        $lantai2 = $dataKos->where('lantai', '2');
        $lantai3 = $dataKos->where('lantai', '3');
        $lantai2_pertama = $lantai2->first();
        $lantai3_pertama = $lantai3->first();
        $lantai2_tersedia = $lantai2->where('status_kamar', 'tersedia');
        $lantai3_tersedia = $lantai3->where('status_kamar', 'tersedia');
    @endphp

    <!-- Compact Lantai 2 Section -->
    <div class="mb-6">
        <div class="flex items-center mb-3">
            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-2">
                <span class="text-white font-bold text-xs">2</span>
            </div>
            <h2 class="text-lg font-bold text-gray-800">Lantai 2</h2>
            <span class="ml-2 text-xs text-gray-600">• {{ $lantai2_tersedia->count() }} kamar tersedia</span>
        </div>
        
        @if($lantai2_pertama)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                {{-- Compact Carousel --}}
                <div class="relative lg:col-span-1">
                    <div class="swiper mySwiperDetail-2 w-full h-60">
                        <div class="swiper-wrapper">
                            @foreach ($lantai2_pertama->foto as $src)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $src) }}" class="w-full h-60 object-cover" alt="Foto Kos Lantai 2">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next swiper-button-next-detail-2 !w-6 !h-6 !bg-white/80 !rounded-full !shadow-md"></div>
                        <div class="swiper-button-prev swiper-button-prev-detail-2 !w-6 !h-6 !bg-white/80 !rounded-full !shadow-md"></div>
                        <div class="swiper-pagination swiper-pagination-detail-2 !bottom-2"></div>
                    </div>
                    
                </div>
                
                {{-- Compact Info --}}
                <div class="lg:col-span-2 p-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-800">Kamar Lantai 2</h3>
                            <div class="bg-blue-100 px-2 py-1 rounded-full">
                                <span class="text-sm font-bold text-blue-600">
                                    Rp{{ number_format($lantai2_pertama->harga_bulanan, 0, ',', '.') }}
                                </span>
                                <span class="text-gray-600 text-xs">/bln</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center text-gray-600 mb-2">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-xs">{{ Str::limit($lantai2_pertama->alamat, 40) }}</span>
                        </div>
                        
                        <div class="flex items-center mb-2">
                            <div class="bg-blue-50 text-blue-700 px-2 py-1 rounded-full text-xs">
                                📐 {{ $lantai2_pertama->luas_kamar }} m²
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <h4 class="text-sm font-semibold text-gray-800 mb-1">Deskripsi</h4>
                            <p class="text-gray-700 text-xs leading-relaxed">{{ Str::limit($lantai2_pertama->deskripsi, 80) }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h4 class="text-sm font-semibold text-gray-800 mb-1">Fasilitas</h4>
                            <div class="grid grid-cols-2 gap-1">
                                @foreach (array_slice($lantai2_pertama->fasilitas, 0, 4) as $item)
                                    <div class="flex items-center text-gray-700">
                                        <span class="text-green-500 mr-1">✓</span>
                                        <span class="text-xs">{{ $item }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-3">
                        <form id="form-pesan-lantai2" action="{{ route('user.pesan.create', ['id' => 'multi']) }}" method="get" onsubmit="return validateCheckboxLogin('kamar2', 'form-pesan-lantai2', {{ Auth::check() ? 'true' : 'false' }});">
                            <label class="block mb-2 font-semibold text-gray-800 text-sm">Pilih Kamar:</label>
                            <div id="kamar2" class="grid grid-cols-2 gap-2 mb-3">
                                @foreach($lantai2_tersedia as $kamar)
                                    <label class="flex items-center space-x-2 p-2 bg-white rounded border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors cursor-pointer">
                                        <input type="checkbox" name="kamar[]" value="{{ $kamar->id }}" class="w-3 h-3 text-blue-600 rounded focus:ring-blue-500">
                                        <span class="text-xs font-medium text-gray-700">{{ $kamar->nomor_kamar }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                                🏠 Pesan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="text-center py-6 bg-gray-50 rounded-lg">
                <p class="text-gray-500 text-sm">Tidak ada kamar di lantai 2</p>
            </div>
        @endif
    </div>

    <!-- Compact Lantai 3 Section -->
    <div class="mb-6">
        <div class="flex items-center mb-3">
            <div class="w-6 h-6 bg-purple-600 rounded-full flex items-center justify-center mr-2">
                <span class="text-white font-bold text-xs">3</span>
            </div>
            <h2 class="text-lg font-bold text-gray-800">Lantai 3</h2>
            <span class="ml-2 text-xs text-gray-600">• {{ $lantai3_tersedia->count() }} kamar tersedia</span>
        </div>
        
        @if($lantai3_pertama)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                {{-- Compact Carousel --}}
                <div class="relative lg:col-span-1">
                    <div class="swiper mySwiperDetail-3 w-full h-60">
                        <div class="swiper-wrapper">
                            @foreach ($lantai3_pertama->foto as $src)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $src) }}" class="w-full h-60 object-cover" alt="Foto Kos Lantai 3">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next swiper-button-next-detail-3 !w-6 !h-6 !bg-white/80 !rounded-full !shadow-md"></div>
                        <div class="swiper-button-prev swiper-button-prev-detail-3 !w-6 !h-6 !bg-white/80 !rounded-full !shadow-md"></div>
                        <div class="swiper-pagination swiper-pagination-detail-3 !bottom-2"></div>
                    </div>
                    
                </div>
                
                {{-- Compact Info --}}
                <div class="lg:col-span-2 p-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-800">Kamar Lantai 3</h3>
                            <div class="bg-purple-100 px-2 py-1 rounded-full">
                                <span class="text-sm font-bold text-purple-600">
                                    Rp{{ number_format($lantai3_pertama->harga_bulanan, 0, ',', '.') }}
                                </span>
                                <span class="text-gray-600 text-xs">/bln</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center text-gray-600 mb-2">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-xs">{{ Str::limit($lantai3_pertama->alamat, 40) }}</span>
                        </div>
                        
                        <div class="flex items-center mb-2">
                            <div class="bg-purple-50 text-purple-700 px-2 py-1 rounded-full text-xs">
                                📐 {{ $lantai3_pertama->luas_kamar }} m²
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <h4 class="text-sm font-semibold text-gray-800 mb-1">Deskripsi</h4>
                            <p class="text-gray-700 text-xs leading-relaxed">{{ Str::limit($lantai3_pertama->deskripsi, 80) }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h4 class="text-sm font-semibold text-gray-800 mb-1">Fasilitas</h4>
                            <div class="grid grid-cols-2 gap-1">
                                @foreach (array_slice($lantai3_pertama->fasilitas, 0, 4) as $item)
                                    <div class="flex items-center text-gray-700">
                                        <span class="text-green-500 mr-1">✓</span>
                                        <span class="text-xs">{{ $item }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-3">
                        <form id="form-pesan-lantai3" action="{{ route('user.pesan.create', ['id' => 'multi']) }}" method="get" onsubmit="return validateCheckboxLogin('kamar3', 'form-pesan-lantai3', {{ Auth::check() ? 'true' : 'false' }});">
                            <label class="block mb-2 font-semibold text-gray-800 text-sm">Pilih Kamar:</label>
                            <div id="kamar3" class="grid grid-cols-2 gap-2 mb-3">
                                @foreach($lantai3_tersedia as $kamar)
                                    <label class="flex items-center space-x-2 p-2 bg-white rounded border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-colors cursor-pointer">
                                        <input type="checkbox" name="kamar[]" value="{{ $kamar->id }}" class="w-3 h-3 text-purple-600 rounded focus:ring-purple-500">
                                        <span class="text-xs font-medium text-gray-700">{{ $kamar->nomor_kamar }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="submit" class="w-full bg-purple-600 text-white px-3 py-2 rounded-lg hover:bg-purple-700 transition-colors font-medium text-sm">
                                🏠 Pesan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="text-center py-6 bg-gray-50 rounded-lg">
                <p class="text-gray-500 text-sm">Tidak ada kamar di lantai 3</p>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Swiper for Lantai 2
            new Swiper(".mySwiperDetail-2", {
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                effect: 'slide',
                speed: 800,
                navigation: {
                    nextEl: ".swiper-button-next-detail-2",
                    prevEl: ".swiper-button-prev-detail-2",
                },
                pagination: {
                    el: ".swiper-pagination-detail-2",
                    clickable: true,
                    dynamicBullets: true,
                },
            });

            // Initialize Swiper for Lantai 3
            new Swiper(".mySwiperDetail-3", {
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                effect: 'slide',
                speed: 800,
                navigation: {
                    nextEl: ".swiper-button-next-detail-3",
                    prevEl: ".swiper-button-prev-detail-3",
                },
                pagination: {
                    el: ".swiper-pagination-detail-3",
                    clickable: true,
                    dynamicBullets: true,
                },
            });

            // Auto-hide error notification
            const errorNotif = document.getElementById('notif-error');
            if (errorNotif) {
                setTimeout(() => {
                    errorNotif.style.opacity = '0';
                    setTimeout(() => {
                        errorNotif.remove();
                    }, 500);
                }, 4000);
            }
        });

        function validateCheckboxLogin(containerId, formId, isLoggedIn) {
            var checkboxes = document.querySelectorAll('#' + containerId + ' input[type=checkbox]:checked');
            
            if (checkboxes.length === 0) {
                showNotification('⚠️ Pilih minimal satu kamar terlebih dahulu!', 'warning');
                return false;
            }
            
            if (!isLoggedIn) {
                showNotification('🔒 Anda harus login terlebih dahulu untuk memesan kamar!', 'info');
                setTimeout(() => {
                    window.location.href = "{{ route('auth.login.form') }}";
                }, 2000);
                return false;
            }
            
            return true;
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 left-1/2 transform -translate-x-1/2 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 ${
                type === 'warning' ? 'bg-yellow-100 border border-yellow-300 text-yellow-800' :
                type === 'error' ? 'bg-red-100 border border-red-300 text-red-800' :
                'bg-blue-100 border border-blue-300 text-blue-800'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="text-sm font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translate(-50%, -100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
    <style>
        /* Custom Swiper Styles for Compact Design */
        .swiper-button-next,
        .swiper-button-prev {
            color: #4F46E5 !important;
            font-weight: bold;
            font-size: 12px !important;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            color: #3730A3 !important;
            transform: scale(1.1);
        }

        .swiper-pagination-bullet {
            background: #6366F1 !important;
            opacity: 0.6;
            width: 6px !important;
            height: 6px !important;
        }

        .swiper-pagination-bullet-active {
            opacity: 1 !important;
            transform: scale(1.3);
            background: #4F46E5 !important;
        }

        /* Fix for Swiper Image Layout */
        .swiper-container,
        .swiper {
            height: 100% !important;
        }

        .swiper-slide {
            height: 100% !important;
        }

        .swiper-slide img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            display: block !important;
        }

        /* Ensure Grid Items Have Equal Height and Remove Gaps */
        .grid.grid-cols-1.lg\\:grid-cols-3 {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 0 !important;
        }

        @media (min-width: 1024px) {
            .grid.grid-cols-1.lg\\:grid-cols-3 {
                grid-template-columns: 1fr 2fr !important;
            }
        }

        /* Remove any unwanted margins and padding */
        .swiper-wrapper {
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Optimize card layout */
        .bg-white.rounded-lg.shadow-md {
            margin-bottom: 0 !important;
        }

        /* Compact Card Animations */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.15);
        }

        /* Checkbox Styling */
        input[type="checkbox"]:checked {
            background-color: currentColor;
            border-color: currentColor;
        }

        /* Custom Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Compact Button Styles */
        .btn-compact {
            transition: all 0.2s ease;
        }

        .btn-compact:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-compact:active {
            transform: translateY(0);
        }

        /* Status Badge Animation */
        .status-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        /* Responsive Improvements */
        @media (max-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
            
            .grid.grid-cols-1.lg\\:grid-cols-3 {
                grid-template-columns: 1fr !important;
            }
            
            .swiper {
                height: 240px !important;
            }
            
            .swiper-slide img {
                height: 240px !important;
            }
        }

        @media (min-width: 769px) and (max-width: 1023px) {
            .grid.grid-cols-1.lg\\:grid-cols-3 {
                grid-template-columns: 1fr 1.5fr !important;
            }
        }

        /* Loading State for Images */
        .swiper-slide img {
            transition: opacity 0.3s ease;
        }

        /* Custom Scrollbar for Mobile */
        .overflow-auto::-webkit-scrollbar {
            width: 4px;
        }

        .overflow-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }

        .overflow-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endsection
