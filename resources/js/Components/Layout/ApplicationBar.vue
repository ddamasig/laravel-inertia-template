<script setup>
import {mdiMenu, mdiBellOutline, mdiHardHat} from "@mdi/js";
import {router} from "@inertiajs/vue3";
import ProfileMenu from "@/Components/Layout/ProfileMenu.vue";

defineProps({
    onToggleNavigationDrawer: Function
})

const navigate = (url) => {
    router.visit(url)
}
</script>
<template>
    <v-app-bar flat>
        <template v-slot:prepend>
            <v-btn :icon="mdiMenu" @click="onToggleNavigationDrawer"></v-btn>
        </template>
        <template v-slot:append>
            <v-btn :icon="mdiBellOutline" color="muted" class="mr-3"/>
            <ProfileMenu/>
        </template>
    </v-app-bar>
</template>
