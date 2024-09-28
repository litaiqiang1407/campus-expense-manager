<template>
    <AuthForm :title="'Sign in'" />
    <div class="flex items-center justify-between mt-4 px-16">
        <a @click.prevent="goToSignin" class="text-[#00BC2A] font-semibold mb-2 text-[14px] text-center">
            Sign up
        </a>
        <a @click.prevent="goForgotPassword" class="text-[#00BC2A] font-semibold mb-2 text-[14px] text-center">
            Forgot password?
        </a>
    </div>
</template>

<script setup>
import AuthForm from '@/Components/Form/AuthForm.vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const goToSignin = () => {
    router.push({ name: 'Signup' });
};

const goForgotPassword = () => {
    router.push({ name: 'ForgotPassword' });
};
</script>