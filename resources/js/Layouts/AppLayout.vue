<script setup>
import {reactive} from 'vue';
import {router} from '@inertiajs/vue3';
import NavigationDrawer from "@/Components/Layout/NavigationDrawer.vue";
import ApplicationBar from "@/Components/Layout/ApplicationBar.vue";
import Breadcrumbs from "@/Components/Layout/Breadcrumbs.vue";

const props = defineProps({
    title: String,
});

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const state = reactive({showNavigationDrawer: true})

const onToggleNavigationDrawerHandler = () => {
    state.showNavigationDrawer = !state.showNavigationDrawer
}

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <v-layout class="rounded rounded-md">
        <NavigationDrawer v-model="state.showNavigationDrawer"/>

        <ApplicationBar :on-toggle-navigation-drawer="onToggleNavigationDrawerHandler"/>

        <v-main>
            <v-container>
                <slot/>
            </v-container>
        </v-main>
    </v-layout>
</template>
