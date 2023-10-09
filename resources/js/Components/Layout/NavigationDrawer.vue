<script setup>
import {
    mdiMonitorDashboard,
    mdiAccountMultiple,
    mdiHardHat,
    mdiSecurity,
    mdiPoliceBadge
} from "@mdi/js";
import {router} from "@inertiajs/vue3";

const links = [
    {
        title: 'Dashboard',
        icon: mdiMonitorDashboard,
        link: '/dashboard'
    },
    {
        title: 'Users',
        icon: mdiAccountMultiple,
        link: '/users'
    },
    {
        title: 'Roles',
        icon: mdiHardHat,
        link: '/roles'
    },
    {
        title: 'Permissions',
        icon: mdiPoliceBadge,
        link: '/permissions'
    },
    {
        title: 'Access Logs',
        icon: mdiSecurity,
        link: '/access-logs'
    },
]

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
        <v-list color="transparent" nav>
            <v-list-item
                v-for="link in links"
                :key="link.link"
                :prepend-icon="link.icon"
                @click="navigate(link.link)"
            >
                <slot name="title">
                    <span style="font-weight: 700; color: #979ea9">
                        {{ link.title }}
                    </span>
                </slot>
            </v-list-item>
        </v-list>

<!--        <template v-slot:append></template>-->
    </v-navigation-drawer>
</template>
