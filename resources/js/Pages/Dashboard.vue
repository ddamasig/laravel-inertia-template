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

        <input v-model="model.search" @input="getUsers"/>
        <ul class="text-white">
            <li v-for="user in users" :key="user.id">
                {{user.name}}
            </li>
        </ul>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
