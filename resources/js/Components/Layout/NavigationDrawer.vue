<script setup>
import {
    mdiMonitorDashboard,
    mdiAccountMultiple,
    mdiHardHat,
    mdiSecurity,
    mdiPoliceBadge
} from "@mdi/js";
import {router} from "@inertiajs/vue3";
import {reactive} from "vue";

const state = reactive({
    activePage: '/'
});

const links = [
    {
        text: 'Dashboard',
        name: 'dashboard',
        icon: mdiMonitorDashboard,
        link: '/dashboard'
    },
    {
        text: 'Users',
        icon: mdiAccountMultiple,
        name: 'users.index',
        link: '/users'
    },
    {
        text: 'Roles',
        name: 'roles.index',
        icon: mdiHardHat,
        link: '/roles'
    },
    {
        text: 'Permissions',
        name: 'permissions.index',
        icon: mdiPoliceBadge,
        link: '/permissions'
    },
    {
        text: 'Access Logs',
        name: 'access-logs.index',
        icon: mdiSecurity,
        link: '/access-logs'
    },
]

const isActivePage = (link) => {
    return link.name === route().current()
}

const navigate = (url) => {
    router.visit(url)
}
</script>

<template>
    <v-navigation-drawer
        color="paper-dark"
        permanent
    >
        <v-list-item
            class="py-3 "
            prepend-avatar="images/dropbox.png"
            title="Generic App"
            subtitle="Some random text here"
        >
        </v-list-item>
        <v-list nav :lines="false" v-model="state.activePage">
            <v-list-item
                v-for="link in links"
                :key="link.link"
                :prepend-icon="link.icon"
                @click="navigate(link.link)"
                :active="isActivePage(link)"
                color="primary"
                :value="link"
            >
                <slot name="title">
                    <span style="font-weight: 700; color: #979ea9">
                        {{ link.text }}
                    </span>
                </slot>
            </v-list-item>
        </v-list>

<!--        <template v-slot:append></template>-->
    </v-navigation-drawer>
</template>
