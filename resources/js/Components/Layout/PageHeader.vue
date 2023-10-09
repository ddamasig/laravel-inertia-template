<script setup>
const props = defineProps({
    title: String
})
</script>

<template>
    <span class="text-h5 font-weight-bold text-paper-dark">
        {{ title }}
    </span>
</template>
