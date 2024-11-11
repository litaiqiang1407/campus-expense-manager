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
            <span class="text-secondaryText text-[12px]">We’ll never post without your permission.</span>
        </div>

        <div class="flex items-center justify-between w-full">
            <div class="border-[1px] border-secondaryText w-full"></div>
            <span class="mx-1 text-[#A1A1A1] text-bold">OR</span>
            <div class="border-[1px] border-secondaryText w-full"></div>
        </div>

        <form class="mt-8" @submit.prevent="submit">
            <div class="flex flex-col space-y-6">
                <div class="flex flex-col gap-2">
                    <InputField v-model="email" type="text" placeholder="Email" />
                    <span v-if="errors.email" class="text-redText text-sm">
                        {{ errors.email }}
                    </span>
                </div>
                
                <div class="flex flex-col gap-2">
                    <InputField v-model="password" type="password" placeholder="Password" />
                    <span v-if="errors.password" class="text-redText text-sm">
                        {{ errors.password }}
                    </span> 
                </div>

                <button type="submit" class="bg-primary text-white rounded py-2 uppercase font-semibold">{{ title }}</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import Header from '@/Components/Header/Index.vue';
import InputField from '@/Components/Form/InputField.vue';
import { ref, defineEmits, watch, toRefs } from 'vue';
import { useRouter } from 'vue-router'; 
import { showAlert, goPage } from '@/Helpers/Helpers';

const emit = defineEmits(['submitted']);
const props = defineProps({
    title: String,
    errors: Object
});

const router = useRouter(); 

const email = ref('');
const password = ref('');
const errors = ref({});
const { errors: propErrors } = toRefs(props);

watch(propErrors, (newValue) => {
    errors.value = newValue;
    if (newValue.emailExist || newValue.emailNotExist) {
        const isExist = newValue.emailExist;
        
        showAlert({
            title: isExist ? 'Email Already Registered' : 'Email Not Registered',
            text: isExist ? 'This email is already registered. Do you want to sign in instead?' 
                          : 'This email is not registered. Do you want to sign up instead?',
            confirmText: isExist ? 'Yes, sign in' : 'Yes, sign up',
            onConfirm: isExist ? goPage(router, 'login') : goPage(router, 'Signup')
        });
    }
});

const validateEmpty = (field, name) => {
    if (!field || field.trim() === '') {
        return `${name} is required.`;
    }
    return '';
};

const validateEmailFormat = (email) => {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        return 'Invalid email format.';
    }
    return '';
};

const validatePasswordLength = (password) => {
    if (password.length < 8) {
        return 'Password must be at least 8 characters.';
    }
    return '';
};

const validateForm = () => {
    errors.value = {};

    const emailEmptyError = validateEmpty(email.value, 'Email');
    const emailFormatError = validateEmailFormat(email.value);
    if (emailEmptyError) {
        errors.value.email = emailEmptyError;
    } else if (emailFormatError) {
        errors.value.email = emailFormatError;
    }

    const passwordEmptyError = validateEmpty(password.value, 'Password');
    const passwordLengthError = validatePasswordLength(password.value);
    if (passwordEmptyError) {
        errors.value.password = passwordEmptyError;
    } else if (passwordLengthError) {
        errors.value.password = passwordLengthError;
    }

    return Object.keys(errors.value).length === 0;
};


const submit = async () => {
    if (validateForm()) {
        try {
            emit('submitted', { email: email.value, password: password.value });
        } catch (error) {
            console.error('Submission error:', error);
        }
    }
};

const loginWithGoogle = () => {
    window.location.href = '/auth/google'; 
};

const loginWithFacebook = () => {
    window.location.href = '/auth/facebook'; 
};
</script>
