<template>
    <AuthForm :title="'Sign up'" />
    <div class="text-center mt-4">
        <a @click.prevent="goToSignin" class="text-primary font-semibold mb-2 text-[14px] text-center">
            Sign in
        </a>
    </div>
</template>

<script setup>
import AuthForm from '@/Components/Form/AuthForm.vue';
import { useRouter } from 'vue-router'; 

const router = useRouter(); 

const goToSignin = () => {
    router.push({ name: 'Signin' });
};
</script>