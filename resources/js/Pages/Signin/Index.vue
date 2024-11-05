<template>
    <AuthForm :title="'Sign in'" @submitted="handleSignin" :loginError="loginError" />
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
import { useRouter } from 'vue-router'; 
import { useToast } from 'vue-toastification';

const router = useRouter(); 
const loginError = ref(''); // Biến lưu trữ thông báo lỗi đăng nhập
const toast = useToast();

const handleSignin = async (formData) => {
    console.log(formData); // Kiểm tra giá trị được gửi
    try {
        await axios.post('/signin', formData);
        toast.success('Login successful!');
        router.push({ name: 'Home' });
    } catch (error) {
        console.error(error.response.data); // In ra thông báo lỗi
        loginError.value = error.response.data.message; // Cập nhật thông báo lỗi
    }
};

const goToSignup = () => {
    router.push({ name: 'Signup' });
};

const goForgotPassword = () => {
    router.push({ name: 'ForgotPassword' });
};
</script>
