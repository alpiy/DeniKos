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
                    'X-Requested-With': 'XMLHttpRequest'
                },
            },
        });

        console.log('Echo initialized');
        console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.content);
        console.log('User ID:', window.Laravel?.userId);
        
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
console.log('Checking user notification setup...');
console.log('window.Laravel:', window.Laravel);

if (window.Laravel && window.Laravel.userId && window.Laravel.userId !== null) {
    console.log('Setting up user notifications for user:', window.Laravel.userId);
    
    const userChannel = window.Echo.private('user.' + window.Laravel.userId);
    
    userChannel
        .listen('.notifikasi-user', (e) => {
             console.log('📢 User notification received:', e);
            const container = document.getElementById('user-realtime-notifikasi');
            if (!container) {
                console.error('Container user-realtime-notifikasi tidak ditemukan');
                return;
            }

            // Pilih warna dan icon berdasarkan tipe
            let bg = 'bg-green-50 border-green-200 text-green-700';
            let icon = `<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>`;
            let titleClass = 'text-green-700';
            let messageClass = 'text-green-800';

            if (e.type === 'danger') {
                bg = 'bg-red-50 border-red-200 text-red-700';
                icon = `<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>`;
                titleClass = 'text-red-700';
                messageClass = 'text-red-800';
            } else if (e.type === 'warning') {
                bg = 'bg-yellow-50 border-yellow-200 text-yellow-700';
                icon = `<svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>`;
                titleClass = 'text-yellow-700';
                messageClass = 'text-yellow-800';
            }

            const notifEl = document.createElement('div');
            notifEl.className = `notification-card ${bg} border shadow-lg p-4 rounded-lg min-w-[260px] max-w-xs animate-fade-in`;
            notifEl.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="mt-1">${icon}</div>
                    <div>
                        <div class="font-semibold ${titleClass}">${e.title}</div>
                        <div class="text-sm mt-1 ${messageClass}">${e.message}</div>
                        <div class="text-xs text-gray-400 mt-2">${new Date().toLocaleTimeString()}</div>
                    </div>
                </div>
            `;

            container.prepend(notifEl);

            setTimeout(() => {
                notifEl.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => notifEl.remove(), 300);
            }, 5000);
        })
        .error((error) => {
            console.error('Channel subscription error:', error);
        });
        
    console.log('User notification setup completed');
} else {
    console.log('User not authenticated or userId is null');
}
}
});