<template>
    <div class="bg-white min-h-screen">
        <div class="grid grid-cols-5 gap-4 p-6">
            <div v-for="(ic, index) in icon" :key="index" class="h-full w-full rounded-full flex 
            items-center justify-center"
            @click="selectIcon(ic)"
            >
                <img :src="ic.path" :alt="ic.name" class="object-cover" />
            </div>
        </div>
    </div>
        
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router'; 

const icon = ref([]); 
const router = useRouter();

const fetchIcon = async () => {
    try {
        const response = await axios.get(route('Icon'));
        icon.value = response.data.icons; 
    } catch (error) {
        console.error('Error fetching icons:', error); 
    } 
};

const selectIcon = (icon) => {
    const walletTypeId = router.currentRoute.value.query.walletTypeId;

    router.push({ name: 'CreateWallet', params: { walletTypeId }, query: { icon: JSON.stringify(icon)} }); 
};

onMounted(fetchIcon);

</script>
