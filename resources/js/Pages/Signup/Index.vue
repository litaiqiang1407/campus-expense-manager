<template>
    <AuthForm :title="'Sign up'" @submitted="handleSignup" />
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
import { useToast } from 'vue-toastification'; // Nếu bạn muốn sử dụng thông báo cho người dùng

const router = useRouter(); 
const toast = useToast();

const handleSignup = async (formData) => {
    console.log(formData); // Kiểm tra giá trị được gửi
    try {
        await axios.post('/register', formData);
        toast.success('Registration successful!');
        router.push({ name: 'Home' });
    } catch (error) {
        console.error(error.response.data); // In ra thông báo lỗi
        toast.error('Registration unsuccessful. Please try again.' + error.response.data.message);
    }
};

const goToSignin = () => {
    router.push({ name: 'login' });
};
</script>
