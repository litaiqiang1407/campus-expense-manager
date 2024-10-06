<template>
    <Form :action="'Save'">
        <InputMoney />
        <Select :selectText="'Select category'" :sizeText="'24'"/>
        <Note />
        <Select :icon="'fa-regular fa-calendar'" :selectText="'Today'" />
        <Select :icon="'wallet'" :selectText="'Select wallet'" />
    </Form>
</template>

<script setup>
import { InputMoney, Select, Note, Form } from '@/Components/Form/Index';
</script>