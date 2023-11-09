<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import {router} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import {reactive} from "vue";
import debounce from 'debounce'

const model = reactive({
    search: ''
})

defineProps({
    users: Array
})

const getUsers = debounce(() => {
    console.log('Search')
    console.log(model)
    router.replace(`dashboard?search=${model.search}`, {
        only: ['users']
    })
}, 500)

</script>

<template>
    <AppLayout title="Dashboard">
    </AppLayout>
</template>
