<script setup>
import {
    mdiMonitorDashboard,
    mdiAccountMultiple,
    mdiHardHat,
    mdiSecurity,
    mdiPoliceBadge, mdiOfficeBuilding
} from "@mdi/js";
import {router} from "@inertiajs/vue3";
import {reactive} from "vue";
import {useAuth} from "@/Composables/useAuth.js";

const state = reactive({
    activePage: '/'
});

const {canEither} = useAuth()
const links = [
    {
        text: 'Dashboard',
        group: 'dashboard',
        icon: mdiMonitorDashboard,
        link: '/dashboard',
        permissions: ['list:dashboards'],
    },
    {
        text: 'Tenants',
        icon: mdiOfficeBuilding,
        group: 'tenants',
        link: '/management/tenants',
        permissions: ['list:tenants'],
    },
    {
        text: 'Users',
        icon: mdiAccountMultiple,
        group: 'users',
        link: '/management/users/',
        permissions: ['list:users'],
    },
    {
        text: 'Roles',
        group: 'roles',
        icon: mdiHardHat,
        link: '/management/roles/',
        permissions: ['list:roles'],
    },
    {
        text: 'Permissions',
        group: 'permissions',
        icon: mdiPoliceBadge,
        link: '/management/permissions/',
        permissions: ['list:permissions'],
    },
    {
        text: 'Access Logs',
        group: 'access-logs',
        icon: mdiSecurity,
        link: '/management/access-logs',
        permissions: ['list:logs'],
    },
]
const allowedLinks = links.filter(link => canEither(link.permissions))

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
        <v-img src="/images/logo.png" max-width="200"/>
        <v-list nav :lines="false" v-model="state.activePage">
            <v-list-item
                v-for="link in allowedLinks"
                :key="link.link"
                :prepend-icon="link.icon"
                @click="navigate(link.link)"
                :active="isActivePage(link)"
                color="primary"
                :value="link"
            >
                <slot name="title">
                    <span
                        :style="`font-weight: 700; color: ${isActivePage(link) ? '#0a66c2' : '#979ea9'}`"
                    >
                        {{ link.text }}
                    </span>
                </slot>
            </v-list-item>
        </v-list>

        <!--        <template v-slot:append></template>-->
    </v-navigation-drawer>
</template>
