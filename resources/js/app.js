import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import Chart from 'chart.js/auto';



import { Navigation, Pagination, Autoplay } from 'swiper/modules';

Swiper.use([Navigation, Pagination, Autoplay]);

document.addEventListener('DOMContentLoaded', () => {
    //swiper daftar kos
    document.querySelectorAll('.swiper').forEach((swiperEl) => {
        const idMatch = swiperEl.className.match(/mySwiper-(\d+)/);
        if (!idMatch) return;

        const kosId = idMatch[1];

        new Swiper(swiperEl, {
            loop: true,
            navigation: {
                nextEl: `.swiper-button-next-${kosId}`,
                prevEl: `.swiper-button-prev-${kosId}`,
            },
            pagination: {
                el: `.swiper-pagination-${kosId}`,
                clickable: true,
            },
        });
    });
    
        // Swiper untuk detail kos
        document.querySelectorAll('.swiper').forEach((swiperEl) => {
            const detailMatch = swiperEl.className.match(/mySwiperDetail-(\d+)/);
            if (detailMatch) {
                const kosId = detailMatch[1];
                new Swiper(swiperEl, {
                    loop: true,
                    navigation: {
                        nextEl: `.swiper-button-next-detail-${kosId}`,
                        prevEl: `.swiper-button-prev-detail-${kosId}`,
                    },
                    pagination: {
                        el: `.swiper-pagination-detail-${kosId}`,
                        clickable: true,
                    },
                });
            }
        });
        // swiper landing page
        console.log("Initializing landing page swiper...");
            new Swiper(".bgSwiper", {
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                speed: 1000,
            });
            // Password visibility toggle functionality
            initPasswordToggle();
             // Hitung Total Pembayaran pada Form Pemesanan
             const hitungTotal = () => {
                const lamaSewaInput = document.getElementById('lama_sewa');
                const totalDisplay = document.getElementById('total_pembayaran_display');
                const totalHidden = document.getElementById('total_pembayaran');
            
                const hargaBulanan = parseInt(lamaSewaInput?.dataset.harga || 0);
                const lamaSewa = parseInt(lamaSewaInput.value) || 0;
                const total = hargaBulanan * lamaSewa;
            
                if (totalDisplay && totalHidden) {
                    totalDisplay.value = total ? `Rp ${total.toLocaleString('id-ID')}` : '';
                    totalHidden.value = total || '';
                }
            };

    const lamaSewaInput = document.getElementById('lama_sewa');
    if (lamaSewaInput) {
        lamaSewaInput.addEventListener('input', hitungTotal);
        hitungTotal(); // Hitung saat pertama kali halaman dibuka jika ada nilai
    }
    

// Hitung Total Biaya Perpanjangan pada Form Perpanjang Sewa
const tambahLamaSewaInput = document.getElementById('tambah_lama_sewa');
const totalPerpanjangDisplay = document.getElementById('total_biaya_perpanjangan_display');
const totalPerpanjangHidden = document.getElementById('total_biaya_perpanjangan');

const hitungTotalPerpanjang = () => {
    if (!tambahLamaSewaInput) return;
    const hargaBulanan = parseInt(tambahLamaSewaInput.dataset.harga || 0);
    const lamaSewa = parseInt(tambahLamaSewaInput.value) || 0;
    const total = hargaBulanan * lamaSewa;

    if (totalPerpanjangDisplay && totalPerpanjangHidden) {
        totalPerpanjangDisplay.value = total ? `Rp ${total.toLocaleString('id-ID')}` : '';
        totalPerpanjangHidden.value = total || '';
    }
};

if (tambahLamaSewaInput) {
    tambahLamaSewaInput.addEventListener('input', hitungTotalPerpanjang);
    hitungTotalPerpanjang(); // Hitung saat pertama kali halaman dibuka jika ada nilai
}
    //grafik pendapatand
    const grafikCanvas = document.getElementById('grafikPendapatan');
    if (grafikCanvas) {
        const chart = new Chart(grafikCanvas, {
            type: 'bar',
            data: {
                labels: JSON.parse(grafikCanvas.dataset.labels || '[]'),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: JSON.parse(grafikCanvas.dataset.data || '[]'),
                    backgroundColor: 'rgba(79, 70, 229, 0.6)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
     // Notifikasi error otomatis hilang setelah 10 detik
    const notif = document.getElementById('notif-error');
    if (notif) {
        setTimeout(() => {
            notif.style.opacity = '0';
            setTimeout(() => notif.remove(), 500);
        }, 10000); // 10 detik
    }
  

        
    // Hitung Total Pembayaran pada Form Pemesanan (multi kamar)
    if (document.getElementById('lama_sewa') && window.hargaKamarList && Array.isArray(window.hargaKamarList) && window.hargaKamarList.length > 0) {
        const lamaSewaInputMulti = document.getElementById('lama_sewa');
        const totalDisplayMulti = document.getElementById('total_pembayaran_display');
        const totalHiddenMulti = document.getElementById('total_pembayaran');
        const hargaList = window.hargaKamarList;
        function updateTotalPembayaranMulti() {
            const lama = parseInt(lamaSewaInputMulti.value) || 0;
            let total = 0;
            hargaList.forEach(harga => {
                total += harga * lama;
            });
            if (totalDisplayMulti && totalHiddenMulti) {
                totalDisplayMulti.value = total > 0 ? 'Rp' + total.toLocaleString('id-ID') : '';
                totalHiddenMulti.value = total || '';
            }
        }
        lamaSewaInputMulti.addEventListener('input', updateTotalPembayaranMulti);
        updateTotalPembayaranMulti();
    }

    // Hitung Total Pembayaran pada Form Pemesanan (multi kamar, DP/lunas)
function updateTotalPembayaranMultiKamar() {
    const lamaSewaInput = document.getElementById('lama_sewa');
    const totalDisplay = document.getElementById('total_pembayaran_display');
    const totalHidden = document.getElementById('total_pembayaran');
    const jenisPembayaran = document.getElementById('jenis_pembayaran');
    const hargaList = window.hargaKamarList;
    if (!lamaSewaInput || !totalDisplay || !totalHidden || !hargaList) return;
    const lama = parseInt(lamaSewaInput.value) || 0;
    let total = 0;
    hargaList.forEach(harga => {
        total += harga * lama;
    });
    let finalTotal = total;
    const jenis = (jenisPembayaran ? jenisPembayaran.value : 'dp');
    if (jenis === 'dp') {
        finalTotal = Math.ceil(total * 0.3); // DP minimal 30%
    }
    totalHidden.value = finalTotal;
    totalDisplay.value = finalTotal > 0 ? 'Rp' + finalTotal.toLocaleString('id-ID') : '';
}

// Inisialisasi event listener untuk form pemesanan multi kamar
function initFormPemesananMultiKamar() {
    const lamaSewaInput = document.getElementById('lama_sewa');
    const jenisPembayaran = document.getElementById('jenis_pembayaran');
    if (!lamaSewaInput) return;
    lamaSewaInput.addEventListener('input', updateTotalPembayaranMultiKamar);
    if (jenisPembayaran) jenisPembayaran.addEventListener('change', updateTotalPembayaranMultiKamar);
    setTimeout(updateTotalPembayaranMultiKamar, 0);
}

    initFormPemesananMultiKamar();
});

// Function to initialize password visibility toggle
function initPasswordToggle() {
    // Handle all password toggle buttons
    document.querySelectorAll('[data-toggle="password"]').forEach(function(toggleButton) {
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const showIcon = this.querySelector('.icon-show');
            const hideIcon = this.querySelector('.icon-hide');
            
            // Debug logging (remove in production)
            console.log('Toggle clicked for:', targetId);
            console.log('Target input found:', !!targetInput);
            console.log('Show icon found:', !!showIcon);
            console.log('Hide icon found:', !!hideIcon);
            
            if (!targetInput || !showIcon || !hideIcon) {
                console.error('Password toggle elements not found:', {
                    targetInput: !!targetInput,
                    showIcon: !!showIcon,
                    hideIcon: !!hideIcon
                });
                return;
            }
            
            if (targetInput.type === 'password') {
                // Show password
                targetInput.type = 'text';
                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
                console.log('Password shown');
            } else {
                // Hide password
                targetInput.type = 'password';
                showIcon.classList.remove('hidden');
                hideIcon.classList.add('hidden');
                console.log('Password hidden');
            }
        });
    });
    
    console.log('Password toggle initialized for', document.querySelectorAll('[data-toggle="password"]').length, 'buttons');
}
