<template>
    <Header />
    <div class="flex flex-col items-center">
            <h1 class="text-[24px] font-bold my-8">{{ title }}</h1>

            <div class="flex flex-col space-y-4 text-center mb-4">
                <button class="flex items-center space-x-8 rounded border-2 px-8 py-2 text-[#0080FF] border-[#0080FF]">
                    <img src="/public/assets/img/facebook.png" alt="Facebook" class="h-6 w-6"/>
                    <span class="uppercase font-semibold">Connect with Facebook</span>
                </button>
                <button class="flex items-center space-x-8 rounded border-2 px-8 py-2 text-[#FF4242] border-[#FF4242]">
                    <img src="/public/assets/img/google.png" alt="Facebook" class="h-6 w-6"/>
                    <span class="uppercase font-semibold">Connect with Google</span>
                </button>
                <span class="text-[#999] text-[10px]">We’ll never post without your permission.</span>
            </div>

            <div class="flex items-center justify-between w-full">
                <div class="border-[1px] border-[#A7A7A7] w-full"></div>
                <span class="mx-1 text-[#A1A1A1] text-bold">OR</span>
                <div class="border-[1px] border-[#A7A7A7] w-full"></div>
            </div>

            <form class="mt-8">
                <div class="flex flex-col space-y-6">
                    <InputField type="email" placeholder="Email" />
                    <InputField type="password" placeholder="Password" />
                    <button type="submit" class="bg-[#00BC2A] text-white rounded py-2 uppercase font-semibold">{{ title }}</button>
                </div>
            </form>
    </div>
</template>

<script setup>
import Header from '@/Components/Header/Index.vue';
import InputField from '@/Components/Form/InputField.vue';

const props = defineProps({
    title: String
});
</script>