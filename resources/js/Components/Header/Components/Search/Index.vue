<template>
    <div class="relative flex-1 h-full">
        <div class="flex items-center gap-2 border-[1px] border-primary rounded-md px-3 h-full">
            <input 
                v-model="localQuery"
                type="text"
                placeholder="Search wallets..."
                class="transition-all flex-1 text-[14px] rounded-md duration-300 ease-in-out w-full  outline-none"
                @focus="isSearching = true"
                @keyup.enter="handleSearch"/>
            <font-awesome-icon icon="fa-regular fa-circle-xmark" class="text-primary cursor-pointer" @click="clearSearch" />
        </div>
      
        <ul v-if="searchHistory.length > 0 && isSearching" class="absolute w-full mx-auto bg-white top-[80%] py-2 rounded-b-md border-r-[1px] border-b-[1px] border-l-[1px] border-primary">
            <li v-for="(query, index) in searchHistory" :key="index" class="button-animate px-4 py-1 flex items-center gap-2 justify-between cursor-pointer hover:bg-hoverPrimary hover:text-white">
                <span @click="selectQuery(query)" class="w-full line-clamp-1">{{ query }}</span>
                <font-awesome-icon icon="xmark" class="text-[12px]" @mousedown.stop="removeSearchQuery(query)" />
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
  initialQuery: {
    type: String,
    default: ''
  },
  historyKey: {
    type: String,
    required: true
  }
});

const emit = defineEmits(['search']);

const localQuery = ref(props.initialQuery);
const searchHistory = ref([]);
const isSearching = ref(false);

const debouncedSearch = debounce((query) => {
  emit('search', query);
  loadSearchHistory();
}, 500);

const loadSearchHistory = () => {
  const history = JSON.parse(localStorage.getItem(props.historyKey) || '[]');
  searchHistory.value = Array.isArray(history) ? history : [];
};

const handleSearch = () => {
  debouncedSearch(localQuery.value);
  isSearching.value = false;
};

const clearSearch = () => {
  localQuery.value = '';
  document.querySelector('input[type="text"]').focus()
  emit('search', localQuery.value);
};

const selectQuery = (query) => {
  localQuery.value = query;
  handleSearch();
};

const removeSearchQuery = (query) => {
  searchHistory.value = searchHistory.value.filter(item => item !== query);
  localStorage.setItem(props.historyKey, JSON.stringify(searchHistory.value));
};

watch(localQuery, (newQuery) => {
  if (newQuery) {
    debouncedSearch(newQuery);
  }
});

watch(() => props.initialQuery, (newQuery) => {
  localQuery.value = newQuery;
});

onMounted(() => {
  loadSearchHistory();
});
</script>