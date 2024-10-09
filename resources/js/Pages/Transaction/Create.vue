<template>
    <div>
        <Form :action="'Save'">
            <InputMoney :inputValue="money" @update:inputValue="money = $event" />
            <Select :selectText="'Select category'" :sizeText="'24'"/>
            <Note />
            <Select :icon="'fa-regular fa-calendar'" :selectText="'Today'" />
            <Select :icon="'wallet'" :selectText="'Select wallet'" />
        </Form>
        
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { InputMoney, Select, Note, Form } from '@/Components/Form/Index';

const money = ref('0');
</script>
