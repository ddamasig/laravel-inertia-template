<script setup>
import {computed} from "vue";
import {useAlertStore} from "@/Stores/AlertStore.js";

const alert = useAlertStore()
const props = defineProps({
    permission: Object,
    modelValue: Object
})

const emit = defineEmits(['update:modelValue'])

const value = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})
</script>

<template>
    <v-checkbox
        v-model="value"
        color="primary"
        :label="permission.name"
        :value="permission"
    />
</template>
