<template>
    <div class="flex flex-col h-screen">
        <header class="h-13 px-4 py-2 flex items-center space-x-4">
            <img src="/public/assets/img/logo.png" alt="Logo" class="h-8 w-8"/>
            <h1 class="text-[#00BC2A] font-bold">Campus Expense</h1>
        </header>
        <main class="flex flex-1 items-center justify-center">
            <swiper
                :modules="modules"
                :slides-per-view="1"
                :space-between="50"
                :loop="true"
                pagination
                :autoplay="{ delay: 3000 }" 
                class="w-full h-[400px]"
            >
                <swiper-slide>
                    <div class="flex flex-col items-center">
                        <img src="/public/assets/img/welcome-banner1.png" alt="Welcome Banner 1" class="w-full h-full object-cover" />
                        <span class="font-semibold text-2xl">Simple money tracker</span>
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="flex flex-col items-center">
                        <img src="/public/assets/img/welcome-banner2.png" alt="Welcome Banner 1" class="w-full h-full object-cover" />
                        <span class="font-semibold text-2xl">Painless budgeting</span>
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="flex flex-col items-center">
                        <img src="/public/assets/img/welcome-banner3.png" alt="Welcome Banner 1" class="w-full h-full object-cover" />
                        <span class="font-semibold text-2xl">The whole picture in one place</span>
                    </div>
                </swiper-slide>
            </swiper>
        </main>
        <footer>
            <div class="flex flex-col items-center">
                <a @click.prevent="goToSignup" class="w-[80%] bg-[#00BC2A] rounded-full uppercase text-center font-semibold text-white py-2 mb-8 text-[12px]">Sign up for free</a>
                <a class="uppercase text-[#00BC2A] font-semibold mb-2 text-[12px]">Sign in</a>
                <span class="text-[8p]">Version 1</span>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router'; 
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';
import { Navigation, Pagination, Scrollbar, Autoplay, A11y } from 'swiper/modules';

const modules = [Navigation, Pagination, Scrollbar, Autoplay, A11y]; 

const router = useRouter(); 

const goToSignup = () => {
    router.push({ name: 'Signup' }); 
};
</script>

<style>

.swiper-pagination-bullets {
    bottom:  10px !important;
}
.swiper-pagination-bullet-active {
  background: #00BC2A !important;
}
</style>
