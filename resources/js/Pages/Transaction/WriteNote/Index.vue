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
import { ref } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();
const noteContent = ref('');

function saveNote() {
    console.log("Save button clicked!", noteContent.value);
    router.push({ name: 'CreateTransaction', query: { note: noteContent.value } });
}
</script>

