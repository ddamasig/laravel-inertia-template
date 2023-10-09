<script setup>
import {router} from "@inertiajs/vue3";

const props = defineProps({
    items: Array
})
const navigate = (url) => {
    router.visit(url)
}

console.log('Breacrumbs:')
console.log(props.items)
</script>

<template>
    <v-breadcrumbs :items="props.items"></v-breadcrumbs>
</template>
