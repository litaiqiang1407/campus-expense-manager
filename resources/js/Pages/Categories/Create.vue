<template>
    <div class="bg-white min-h-[calc(100vh-72px)]">
        <Form :action="'Save'" @submit="submitForm">
            <div class="flex items-center gap-4">
                <Select :selectText="''" @click="selectIcon" :iconSrc="selectedIcon?.path || '/assets/icon/income/salary.png'" :hideText="true"/>
                <Input v-model="input" :placeholder="'Category Name'" />
            </div>
            <div class="flex items-center gap-7 my-3">
                <font-awesome-icon icon="plus-minus" class="text-black size-[16px]" />
                <span class="text-black py-4 border-t-[0.5px] border-b-[0.5px] w-full capitalize">{{ categoryType }}</span>
            </div>
            <Select :iconSrc="categoryIcon" :selectText="selectedCategory ? selectedCategory : 'Select category'"
                :sizeText="'16'" :getItemLabel="item => item.name" @update:selectText="updateCategory"
                @click="goPage('ParentCategories')" />
            <Submit>Save</Submit>
        </Form>
    </div>
</template>
<script setup>
import { Select, Form, Input } from '@/Components/Form/Index';
import Submit from '@/Components/Button/Submit/Index.vue';
import { useToast } from 'vue-toastification';
import { ref, watch } from 'vue';
import { goSelect } from '@/Helpers/Helpers';
import { useRouter } from 'vue-router';
const getLocalStorageItem = (key, defaultValue = null) => {
    const item = localStorage.getItem(key);
    try {
        return item ? JSON.parse(item) : defaultValue;
    } catch (error) {
        return item || defaultValue;
    }
};

const goPage = (page) => {
    router.push({
        name: page, query: {
            fromPage: 'AddCategory',
        }
    });
};

const selectedCategory = ref(getLocalStorageItem('selectedCategory', null));
const categoryIcon = ref(getLocalStorageItem('CategoryIcon', null));
const category_id = ref(getLocalStorageItem('categoryId', null));

const router = useRouter();
const selectedIcon = ref(JSON.parse(localStorage.getItem('selectedIcon')) || []);
const categoryType = ref(getLocalStorageItem('type', "expense"));
const input = ref(getLocalStorageItem('input', "New Category"));
const toast = useToast();

watch(input, (newValue) => {
    localStorage.setItem('input', JSON.stringify(newValue));
});

const selectIcon = () => {
//   localStorage.setItem('walletName', walletName.value);
//   localStorage.setItem('balance', balance.value);
//   localStorage.setItem('walletType', walletType.value);

  goSelect(router, 'icon')
};

const submitForm = async () => {
    try {
        const formData = {
            name: input.value,
            parent_id: category_id.value,
            type: 'expense',
            icon_id: selectedIcon.value.id || 36,
        };
        console.log(formData)
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
