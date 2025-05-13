import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

document.addEventListener('DOMContentLoaded', function() {
    if (!window.Echo) {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            },
        });

        console.log('Echo initialized for admin');
        
        window.Echo.channel('notifikasi-admin')
            .listen('.pemesanan-baru', (e) => {
                console.log('📢 Notifikasi diterima:', e);
                
                const container = document.getElementById('realtime-notifikasi');
                if (!container) {
                    console.error('Container notifikasi tidak ditemukan');
                    return;
                }
 // 1. Buat element notifikasi
        const notifEl = document.createElement('div');
        notifEl.className = 'notification-card bg-blue-50 p-4 mb-2 rounded-lg border border-blue-200 animate-fade-in';
        notifEl.innerHTML = `
            <div class="flex justify-between">
                <div>
                    <strong class="font-medium">Pemesanan Baru</strong>
                    <p class="text-sm mt-1">${e.message}</p>
                </div>
                <span class="text-xs text-gray-500">${new Date().toLocaleTimeString()}</span>
            </div>
        `;

        // 2. Tambahkan ke container
        container.prepend(notifEl);

        // 3. Auto-hide setelah 5 detik dengan animasi
        setTimeout(() => {
            notifEl.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            setTimeout(() => notifEl.remove(), 300); // Hapus setelah animasi
        }, 5000); // Sesuaikan waktu (dalam milidetik)
    });
}
});