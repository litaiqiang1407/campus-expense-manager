<template>
    <Header />
    <div class="flex flex-col items-center">
        <h1 class="text-[24px] font-bold my-8">{{ title }}</h1>

        <div class="flex flex-col space-y-4 text-center mb-4">
            <button @click="loginWithFacebook" class="flex items-center space-x-8 rounded border-2 px-8 py-2 text-blueText border-blueText">
                <img src="/assets/img/facebook.png" alt="Facebook" class="h-6 w-6"/>
                <span class="uppercase font-semibold">Connect with Facebook</span>
            </button>
            <button @click="loginWithGoogle" class="flex items-center space-x-8 rounded border-2 px-8 py-2 text-redText border-redText">
                <img src="/assets/img/google.png" alt="Google" class="h-6 w-6"/>
                <span class="uppercase font-semibold">Connect with Google</span>
            </button>
            <span class="text-[#999] text-[10px]">We’ll never post without your permission.</span>
        </div>

        <div class="flex items-center justify-between w-full">
            <div class="border-[1px] border-secondaryText w-full"></div>
            <span class="mx-1 text-[#A1A1A1] text-bold">OR</span>
            <div class="border-[1px] border-secondaryText w-full"></div>
        </div>

        <form class="mt-8" @submit.prevent="submit">
            <div class="flex flex-col space-y-6">
                <InputField v-model="email" type="text" placeholder="Email" />
                <span v-if="emailError" class="text-redText text-sm">{{ emailError }}</span>
                <!-- Chỉ hiển thị thông báo lỗi cho email -->
                
                <InputField v-model="password" type="password" placeholder="Password" />
                <span v-if="passwordError && emailExists" class="text-redText text-sm">{{ passwordError }}</span> <!-- Hiển thị lỗi mật khẩu nếu email tồn tại -->

                <!-- Di chuyển thông báo lỗi login dưới trường mật khẩu -->
                <span v-if="loginError && !passwordError" class="text-redText text-sm">{{ loginError }}</span> <!-- Hiển thị lỗi mật khẩu -->

                <button type="submit" class="bg-primary text-white rounded py-2 uppercase font-semibold">{{ title }}</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import Header from '@/Components/Header/Index.vue';
import InputField from '@/Components/Form/InputField.vue';
import { ref, defineEmits, defineProps } from 'vue';

const emit = defineEmits(['submitted']);
const props = defineProps({
    title: String,
    loginError: String // Nhận thông báo lỗi từ component cha
});

const email = ref('');
const password = ref('');
const emailError = ref('');
const passwordError = ref('');
const emailExists = ref(false); // Biến để kiểm tra email tồn tại

const submit = async () => {
    emailError.value = '';
    passwordError.value = '';

    if (email.value.trim() === '') {
        emailError.value = 'Email is required';
        return;
    } else if (!validateEmail(email.value)) {
        emailError.value = 'Invalid email format';
        return;
    }

    // Kiểm tra email đã tồn tại hay chưa
    await checkEmailExists(email.value);

    if (!emailExists.value) {
        return; // Nếu email không tồn tại, dừng lại
    }

    // Nếu người dùng đã nhập mật khẩu
    if (password.value.trim() === '') {
        passwordError.value = 'Password is required';
    } else if (password.value.length < 8) {
        passwordError.value = 'Password must be at least 8 characters';
    }

    // Chỉ gửi dữ liệu nếu không có lỗi
    if (emailError.value === '' && passwordError.value === '') {
        emit('submitted', {
            email: email.value,
            password: password.value,
        });
    }
};

// Hàm kiểm tra email đã tồn tại trong database
const checkEmailExists = async (email) => {
    try {
        // Gửi request để kiểm tra email tồn tại
        const response = await axios.post('/check-email', { email });
        // Nếu tồn tại, đặt biến emailExists thành true
        emailExists.value = true;
    } catch (error) {
        // Nếu không tồn tại, cập nhật thông báo lỗi
        emailError.value = 'Email does not exist. Please register.';
        emailExists.value = false; // Đặt biến thành false
    }
};

const validateEmail = (email) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
};

const loginWithGoogle = () => {
    window.location.href = '/auth/google'; 
};

const loginWithFacebook = () => {
    window.location.href = '/auth/facebook'; 
};
</script>
