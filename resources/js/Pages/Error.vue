<script setup>
import {computed} from 'vue'

const props = defineProps({status: Number})

const title = computed(() => {
    return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
    }[props.status]
})

const description = computed(() => {
    return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
    }[props.status]
})
</script>

<template>
    <v-container fluid class="pa-0" style="height: 100vh">
        <v-row class="bg-blue ma-0 fill-height" justify="center">
            <v-col cols="12" class="bg-paper-dark" style="display: flex; flex-direction: column">
                <div class="my-auto">
                    <h1 class="text-center">{{ title }}</h1>
                    <p class="text-center">{{ description }}</p>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>
