<template>
    <AuthForm :title="'Sign in'" @submitted="handleSignin" :errors="errors" />
    <div class="flex items-center justify-between mt-4 px-16">
        <a @click.prevent="goToSignup" class="text-primary font-semibold mb-2 text-[14px] text-center">
            Sign up
        </a>
        <a @click.prevent="goForgotPassword" class="text-primary font-semibold mb-2 text-[14px] text-center">
            Forgot password?
        </a>
    </div>
</template>

<script setup>
import AuthForm from '@/Components/Form/AuthForm.vue';
import { ref } from 'vue'; 
import { goPage } from '@/Helpers/Helpers';
import { useRouter } from 'vue-router'; 
import { useToast } from 'vue-toastification';

const router = useRouter(); 
const toast = useToast();

const errors = ref({});

const handleSignin = async (formData) => {
    try {
        await axios.post('/signin', formData);
        toast.success('Login successful!');
        router.push({ name: 'Home' });
    } catch (error) {
        errors.value = {};
        if (error.response.data) {
            errors.value[error.response.data.type] = error.response.data.error;
        }
    }
};

const goToSignup = () => {
    goPage(router, 'Signup');
};

const goForgotPassword = () => {
    goPage(router, 'ForgotPassword');
};
</script>
