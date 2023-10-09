<script setup>
import {router} from "@inertiajs/vue3";


const items = [
    {
        title: 'My Profile',
        link: '/profile'
    },
    {
        title: 'Settings',
        link: '/settings'
    },
    {
        title: 'Logout',
        link: '/logout'
    },
]

const navigate = (url) => {
    router.visit(url)
}
</script>
<template>
    <v-menu>
        <template v-slot:activator="{ props }">
            <v-avatar
                image="https://i.pravatar.cc/150"
                class="cursor-pointer"
                v-bind="props"
            />
        </template>

        <v-list>
            <v-list-item
                v-for="(item, i) in items"
                :key="i"
                @click="navigate(item.link)"
            >
                <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>
