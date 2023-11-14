<script setup>
import {reactive} from 'vue';
import {router} from '@inertiajs/vue3';
import NavigationDrawer from "@/Components/Layout/NavigationDrawer.vue";
import ApplicationBar from "@/Components/Layout/ApplicationBar.vue";
import Breadcrumbs from "@/Components/Layout/Breadcrumbs.vue";
import Alert from "@/Components/Common/Notifications/Alert.vue";
import ConfirmationModal from "@/Components/Common/Modals/ConfirmationModal.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";

const props = defineProps({
    title: String,
    breadcrumbs: Breadcrumbs,
    isFluid: Boolean
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
            <v-container class="pt-0" :fluid="props.isFluid">
                <v-row>
                    <v-col cols="12">
                        <Alert/>
                        <ConfirmationModal/>
                    </v-col>
                </v-row>

                <div style="flex-grow: 0">
                    <PageHeader :title="props.title"/>
                    <Breadcrumbs :items="props.breadcrumbs"/>
                </div>

                <v-divider class="mt-3"/>

                <slot/>
            </v-container>
        </v-main>
    </v-layout>
</template>
