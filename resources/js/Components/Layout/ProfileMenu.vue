<script setup>
import {router, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import {mdiAccount} from "@mdi/js";


const items = [
    {
        title: 'My Profile',
        link: '/profile',
        method: 'GET'
    },
    {
        title: 'Settings',
        link: '/settings',
        method: 'GET'
    },
    {
        title: 'Logout',
        link: '/logout',
        method: 'POST'
    },
]

const navigate = ({link, method}) => {
    router.visit(link, {
        method
    })
}

const page = usePage();
const user = computed(() => page.props.auth.user)

</script>
<template>
    <v-menu>
        <template v-slot:activator="{ props }">
            <v-avatar
                :image="user.avatar?.original_url"
                :icon="mdiAccount"
                color="muted"
                class="cursor-pointer"
                v-bind="props"
            />
        </template>

        <v-list>
            <v-list-item class="border-b pb-3">
                <v-list-item-title class="font-weight-bold">{{ user.full_name }}</v-list-item-title>
                <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
            </v-list-item>
            <v-list-item
                v-for="(item, i) in items"
                :key="i"
                @click="navigate(item)"
            >
                <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>
