<template>
    <div class="">
        <button class="flex items-center p-4 bg-white w-full gap-4" @click="goPage('AddCategory','income')">
            <font-awesome-icon icon="circle-plus" class="text-primary size-8" />
            <span class="text-primary font-bold">NEW CATEGORY</span>
        </button>

        <div v-for="category in getCategory()" :key="category.id" class="bg-white shadow my-2 py-2">
            <div class="flex justify-between items-center py-2 px-4" @click="gotoback(category)">
                <div class="flex items-center space-x-3">
                    <img :src="category.icon_path" alt="Category Icon" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-medium">{{ category.name }}</p>
                    </div>
                </div>
                <button class="absolute right-0 pr-3">
                    <font-awesome-icon icon="angle-right" class="text-secondaryText size-5" />
                </button>
            </div>

            <ul v-if="getSubcategories(category.parent_id).length && router.currentRoute.value.name !== 'ParentCategories'" class="pl-8">
                <li v-for="(subcategory, index) in getSubcategories(category.id)" :key="subcategory.id" :class="[
                    'flex items-center space-x-2 py-2',
                    index === getSubcategories(category.id).length - 1 ? 'border-left-half' : 'border-l-2'
                ]" @click="gotoback(subcategory)">
                    <div class="h-[2px] bg-gray-200 w-2 absolute"></div>
                    <img :src="subcategory.icon_path" alt="Subcategory Icon" class="w-8 h-8 rounded-full">
                    <div>
                        <p class="font-medium">{{ subcategory.name }}</p>
                    </div>
                    <button class="absolute right-0 pr-3">
                        <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router'; // Import useRouter to handle navigation
import { computed } from 'vue';

const router = useRouter();

const props = defineProps({
    categories: Array
});

// Filter categories to get main categories (without subcategories)
const getCategory = () => {
    return props.categories.filter(category => category.parent_id !== null);
};

// Filter subcategories based on parentId
const getSubcategories = (parentId) => {
    return props.categories.filter(category => category.parent_id === parentId);
};

// Check if the current route is select-categories
const isSelectCategoryPage = computed(() =>
    router.currentRoute.value.name === 'SelectCategories' ||
    router.currentRoute.value.name === 'ParentCategories'
);

function goPage(routeName, type) {
    localStorage.setItem('type', type);
    router.push({
        name: routeName,
    });
}
const gotoback = (category) => {
    console.log('Selected Category:', category);
    localStorage.setItem('categoryId', category.id);
    localStorage.setItem('selectedCategory', category.name);
    localStorage.setItem('CategoryIcon', category.icon_path);
    const fromPage = router.currentRoute.value.query.fromPage;
    const id = router.currentRoute.value.query.id;
    if (isSelectCategoryPage.value) {
        if (fromPage) {
            router.push({
                name: fromPage,
                params: { id: id },
            });
        } else {
            console.error('fromPage is not defined or is invalid');
        }
    }
};
</script>

<style>
.border-left-half {
    position: relative;
}

.border-left-half::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 1.9px;
    height: 50%;
    background-color: #e5e7eb;
    z-index: 1;
}
</style>
