<template>
    <div class="flex flex-col space-y-2">
        <div class="flex items-center justify-between">
            <span class="font-semibold text-[10px] text-secondaryText">Top spending</span>
            <button class="text-primary font-semibold text-[10px]" @click="showPopup">See details</button>
        </div>
        <div class="p-4 rounded-lg shadow bg-white">
            <div class="flex items-center p-1 bg-[#f0f0f0] rounded-[8px]">
                <button 
                    class="w-1/2 rounded-[4px] font-medium text-[10px] py-2" 
                    :class="activeButton === 'week' ? 'bg-white' : 'bg-[#f0f0f0'" 
                    @click="setActive('week')">
                    Week
                </button>
                <button class="w-1/2 rounded-[4px] font-medium text-[10px] py-2" 
                :class="activeButton === 'month' ? 'bg-white' : 'bg-[#f0f0f0'" 
                @click="setActive('month')">Month</button>
            </div>
            <div class="flex flex-col mt-2">
                <div v-if="isLoading" class="w-full h-[79px] flex items-center justify-center">
                    <Loading class="size-8"/>
                </div>
                <div v-if="!isLoading && displayTopSpending.length === 0" class="flex flex-col items-center text-center py-8">
                    <span class="font-semibold text-[10px] text-secondaryText">Top spending categories will show up here 🙌</span>
                </div>
                <div v-else-if="!isLoading && displayTopSpending.length > 0">
                    <div v-for="(item, index) in displayTopSpending" :key="index" class="py-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <img :src="item?.category_icon_path || '/assets/icon/total.png'" class="w-8 h-8" alt="icon" />
                                <div class="flex flex-col">
                                    <span class="font-semibold text-[10px]">{{ item?.name || "Spending"}}</span>
                                    <span class="text-[10px] text-secondaryText">{{ formatBalance(item?.total_amount) || '99' }}</span>
                                </div>
                            </div>
                            <span class="font-semibold text-[10px] text-redText">
                                {{ item?.percentage || '99' }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <Popup ref="popup" title="Coming Soon!" message="This feature is under development." />
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { formatBalance } from '@/Helpers/Helpers';
import Popup from '@/Components/Popup/Index.vue';
import Loading  from '@/Components/Loading/Index.vue'; 

const props = defineProps({
    topSpending: Array,
    isLoading: Boolean
});

const isLoading = ref(false);
const activeButton = ref('month');
const topSpending = ref([]); 
const popup = ref(null);

const showPopup = () => {
    popup.value.openPopup();
};

watch(
    () => props.topSpending,
    (newVal) => {
        topSpending.value = [...newVal];
    },
    { immediate: true } 
);
watch(
    () => props.isLoading,
    (newVal) => {
        isLoading.value = newVal;
    },
    { immediate: true } 
);

const setActive = async  (button) => {
    activeButton.value = button;
    await fetchTopSpending(button);
};

const fetchTopSpending = async (filter) => {
    try {
        isLoading.value = true;
        const response = await axios.get(route('Home'), { params: { filter } });
        topSpending.value = response.data.topSpending;
    } catch (error) {
        console.error('Error fetching top spending:', error);
    } finally {
        isLoading.value = false;
    }
};

const displayTopSpending = computed(() => topSpending.value);
</script>