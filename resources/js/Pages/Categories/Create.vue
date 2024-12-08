<template>
    <div class="bg-white min-h-[calc(100vh-72px)]">
        <Form :action="'Save'" @submit="submitForm">
            <div class="flex items-center gap-4">
                <Select :selectText="''" />
                <Input v-model="input" :placeholder="'Category Name'" />
            </div>
            <div class="flex items-center gap-7 my-3">
                <font-awesome-icon icon="plus-minus" class="text-black size-[16px]" />
                <span class="text-black py-4 border-t-[0.5px] border-b-[0.5px] w-full">Expense</span>
            </div>
            <div class="flex items-center gap-7">
                <font-awesome-icon icon="layer-group" class="text-black size-[16px]" />
                <div class="flex-1">
                    <span class="text-secondaryText font-extralight text-[16px]">Parent category</span>
                    <p class="text-[20px] text-gray-400">Select category</p>
                </div>
                <font-awesome-icon icon="angle-right" class="text-secondaryText size-5" />
            </div>
            <Submit>Save</Submit>
        </Form>
    </div>
</template>
<script setup>
import { Select, Form, Input } from '@/Components/Form/Index';
import Submit from '@/Components/Button/Submit/Index.vue';
import { useToast } from 'vue-toastification';
import { ref, watch } from 'vue';

const getLocalStorageItem = (key, defaultValue = null) => {
    const item = localStorage.getItem(key);
    try {
        return item ? JSON.parse(item) : defaultValue;
    } catch (error) {
        return item || defaultValue;
    }
};

const input = ref(getLocalStorageItem('input', "New Category"));
const toast = useToast();

watch(input, (newValue) => {
    localStorage.setItem('input', JSON.stringify(newValue));
});

const submitForm = async () => {
    try {
        const formData = {
            name: input.value, 
            parent_id: null,
            type: 'expense',
            icon_id: 4,
        };
        const response = await axios.post(route('StoreCategory'), formData);
        if (response.data.success) {
            localStorage.clear();
            toast.success(response.data.message);
            window.location.href = '/categories';
        } else {
            toast.error('Failed to create category.');
        }
    } catch (error) {
        const message = error.response?.data?.message || error.message || 'An unknown error occurred';
        toast.error('Error creating category: ' + message);
    }
};
</script>
