<script setup>
const props = defineProps({
    title: String
})
</script>

<template>
    <span class="text-h3 font-weight-bold">
        {{ title }}
    </span>
</template>
