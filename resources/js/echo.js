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
   // ...existing code...

// === USER NOTIFIKASI REALTIME ===
if (window.Laravel && window.Laravel.userId) {
    window.Echo.channel('user.' + window.Laravel.userId)
        .listen('.notifikasi-user', (e) => {
            // Container notifikasi user
            const container = document.getElementById('user-realtime-notifikasi');
            if (!container) return;

            // Card notifikasi
            const notifEl = document.createElement('div');
            notifEl.className = 'notification-card bg-green-50 border border-green-200 shadow-lg p-4 rounded-lg min-w-[260px] max-w-xs animate-fade-in';
            notifEl.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="mt-1">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-green-700">${e.title}</div>
                        <div class="text-sm text-green-800 mt-1">${e.message}</div>
                        <div class="text-xs text-gray-400 mt-2">${new Date().toLocaleTimeString()}</div>
                    </div>
                </div>
            `;

            // Tambahkan ke container
            container.prepend(notifEl);

            // Auto-hide setelah 5 detik
            setTimeout(() => {
                notifEl.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => notifEl.remove(), 300);
            }, 5000);
        });
}
}
});