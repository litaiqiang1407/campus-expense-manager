<template>
    <div class="bg-primaryBackground h-screen p-4">
        <textarea
            class="w-full p-4 border rounded"
            v-model="noteContent"
            rows="10"
            cols="50"
            placeholder="Write note here..."
        ></textarea>
        <button
            class="mt-4 p-2 bg-primary w-full text-white text-[16px] rounded-[1000px]"
            @click="saveNote"
        >
            Save
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const noteContent = ref('');

onMounted(() => {
    noteContent.value = router.currentRoute.value.query.value || '';
});

function saveNote() {
    localStorage.setItem('note', noteContent.value);
    const fromPage = router.currentRoute.value.query.fromPage;
    const transactionId = router.currentRoute.value.query.transactionId;
    router.push({
        name: fromPage,
        params: { transactionId: transactionId },
    });
}
</script>
