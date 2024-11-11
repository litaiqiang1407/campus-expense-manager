<template>
    <AuthForm :title="'Sign up'" @submitted="handleSignup" :errors="errors" />
    <div class="text-center mt-4">
        <a @click.prevent="goToSignin" class="text-primary font-semibold mb-2 text-[14px] text-center">
            Sign in
        </a>
    </div>
</template>

<script setup>
import AuthForm from '@/Components/Form/AuthForm.vue';
import { useRouter } from 'vue-router'; 
import { ref } from 'vue';
import { goPage } from '@/Helpers/Helpers';
import { useToast } from 'vue-toastification';

const router = useRouter(); 
const toast = useToast();

const errors = ref({});

const handleSignup = async (formData) => {
    try {
        const response = await axios.post('/register', formData);
        toast.success('Registration successful!');
        router.push({ name: 'Home' });
    } catch (error) {
        errors.value = {};
        if (error.response.data) {
            errors.value[error.response.data.type] = error.response.data.error;
        }
    }
};

const goToSignin = () => {
    goPage(router, 'login');
};
</script>
