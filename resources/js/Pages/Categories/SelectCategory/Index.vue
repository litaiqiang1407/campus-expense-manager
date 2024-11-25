<template>
    <div>
        <button class="flex items-center p-4 bg-white w-full gap-4">
            <font-awesome-icon icon="circle-plus" class="text-primary size-8" />
            <span class="text-primary font-bold">NEW CATEGORY</span>
        </button>

        <div v-for="category in categories" :key="category.id" class="bg-white shadow my-2 py-2" @click="selectCategory(category)">
            <div class="flex justify-between items-center py-2 px-4">
                <div class="flex items-center space-x-3">
                    <img :src="category.icon_path" alt="Category Icon" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-medium">{{ category.name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const categories = ref([]);
const router = useRouter();

const fetchCategories = async () => {
    try {
        const response = await axios.get(route('Categories'));
        categories.value = response.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

const selectCategory = (category) => {
    router.push({ name: 'CreateBudget', query: { icon: category.icon_path, name: category.name, id: category.id } });
};

onMounted(() => {
    fetchCategories();
});
</script>
