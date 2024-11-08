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
            class="mt-4 p-2 bg-blue-500 text-white rounded"
            @click="saveNote"
        >
            Save
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const noteContent = ref('');

onMounted(() => {
    noteContent.value = route.query.note || '';
});

function saveNote() {
    localStorage.setItem('note', noteContent.value);

    const transactionId = route.query.transactionId || route.params.transactionId;

    if (transactionId) {
        router.push({
            name: 'EditTransaction',
            params: { transactionId: transactionId },
            query: { note: noteContent.value }
        });
    } else {
        console.error("transactionId is missing from route query or params.");
    }
}
</script>
