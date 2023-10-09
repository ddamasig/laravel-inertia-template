<script setup>
const props = defineProps({
    title: String
})
</script>

<template>
    <span class="text-h4 font-weight-bold">
        {{ title }}
    </span>
</template>
