<script setup>
import {router} from "@inertiajs/vue3";

const props = defineProps({
    items: Array
})
const navigate = (url) => {
    router.visit(url)
}
</script>

<template>
    <v-breadcrumbs
        class="px-0 pb-0 pt-2"
        color="primary"
        :items="props.items"
    />
</template>

<style>
.v-breadcrumbs-item--disabled {
    opacity: 0.7 !important;
}
.v-breadcrumbs-item {
    padding-left: 0 !important;
}
</style>
