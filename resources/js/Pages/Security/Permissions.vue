<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {router} from "@inertiajs/vue3";
import {reactive} from "vue";
import debounce from 'debounce'

const model = reactive({
    search: ''
})

defineProps({
    breadcrumbs: Array,
    users: Array
})

const getUsers = debounce(() => {
    router.replace(`dashboard?search=${model.search}`, {
        only: ['users']
    })
}, 500)

</script>

<template>
    <AppLayout>
        <span class="text-h3 font-weight-bold">Permissions</span>
    </AppLayout>
</template>
