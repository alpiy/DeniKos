import './bootstrap';
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
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
             // Toggle password visibility
            const togglePassword = (passwordId, iconId) => {
                const passwordField = document.getElementById(passwordId);
                const eyeIcon = document.getElementById(iconId);
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    eyeIcon.classList.add('text-indigo-600');
                } else {
                    passwordField.type = 'password';
                    eyeIcon.classList.remove('text-indigo-600');
                }
            };

            // Add event listeners for password visibility toggles
            document.querySelectorAll('.toggle-password').forEach(button => {
                const targetPassword = button.getAttribute('data-target');
                const targetIcon = button.getAttribute('data-icon');
                button.addEventListener('click', () => togglePassword(targetPassword, targetIcon));
            });
        
    

});
