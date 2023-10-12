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
        group: 'dashboard',
        icon: mdiMonitorDashboard,
        link: '/dashboard'
    },
    {
        text: 'Users',
        icon: mdiAccountMultiple,
        group: 'users',
        link: '/users'
    },
    {
        text: 'Roles',
        group: 'roles',
        icon: mdiHardHat,
        link: '/roles'
    },
    {
        text: 'Permissions',
        group: 'permissions',
        icon: mdiPoliceBadge,
        link: '/permissions'
    },
    {
        text: 'Access Logs',
        group: 'access-logs',
        icon: mdiSecurity,
        link: '/access-logs'
    },
]

const isActivePage = (link) => {
    return route().current().startsWith(link.group)
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
            class="py-12"
            prepend-avatar="/images/logo.png"
            title="Prototype App"
            subtitle="Some random text here"
        />
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
                    <span
                        :style="`font-weight: 700; color: ${isActivePage(link) ? 'white' : '#979ea9'}`"
                    >
                        {{ link.text }}
                    </span>
                </slot>
            </v-list-item>
        </v-list>

<!--        <template v-slot:append></template>-->
    </v-navigation-drawer>
</template>
