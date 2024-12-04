<template>
    <div class="bg-white min-h-screen">
        <div v-if="isLoading" class="flex w-screen items-center justify-center h-64">
            <Loading class="size-16"/>
        </div>
        <div class="grid grid-cols-5 gap-4 p-6">
            <div v-for="(ic, index) in icon" :key="index" class="h-full w-full rounded-full flex 
            items-center justify-center"
            @click="selectIcon(ic)"
            >
                <img v-if="ic.name != 'Total Icon'" :src="ic?.path || '/assets/icon/box.png'" :alt="ic.name" class="object-cover" />
            </div>
        </div>
    </div>
        
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import  Loading  from '@/Components/Loading/Index.vue'; 

const icon = ref([]); 
const router = useRouter();
const isLoading = ref(false);

const fetchIcon = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get(route('Icon'));
        icon.value = response.data.icons; 
    } catch (error) {
        console.error('Error fetching icons:', error); 
    }  finally {
        isLoading.value = false;
    }
};

const selectIcon = (icon) => {
    const walletType = router.currentRoute.value.query.walletType;
    const walletId = router.currentRoute.value.query.walletId;

    if (walletId) {
        router.push({  name: 'AddCategory', query: { iconId: icon.id }});
    } else if (walletType) {
        router.push({ name: 'CreateWallet', params: { walletType }, query: { iconId: icon.id }});
    }
};

onMounted(fetchIcon);

</script>
