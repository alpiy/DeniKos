import './bootstrap';
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Navigation, Pagination } from 'swiper/modules';

Swiper.use([Navigation, Pagination]);

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
    

});
