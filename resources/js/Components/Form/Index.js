import { defineAsyncComponent } from 'vue';
export const InputMoney = defineAsyncComponent(() => import('./CRUD/InputMoney.vue'));
export const Select = defineAsyncComponent(() => import('./CRUD/Select.vue'));
export const Note = defineAsyncComponent(() => import('./CRUD/Note.vue'));
export const Form = defineAsyncComponent(() => import('./CRUD/Form.vue'));
export const Input = defineAsyncComponent(() => import('./CRUD/Input.vue'));
export const DateTimePicker = defineAsyncComponent(() => import('./CRUD/DateTimePicker.vue'));
