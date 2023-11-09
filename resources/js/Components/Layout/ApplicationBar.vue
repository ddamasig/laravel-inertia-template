<script setup>
import {mdiMenu, mdiBellOutline, mdiHardHat} from "@mdi/js";
import {router, usePage} from "@inertiajs/vue3";
import ProfileMenu from "@/Components/Layout/ProfileMenu.vue";
import {computed} from "vue";

defineProps({
  onToggleNavigationDrawer: Function
})

const navigate = (url) => {
  router.visit(url)
}

const page = usePage();
const user = computed(() => page.props.auth.user)

const channel = `App.Models.User.${user.value.id}`
console.log(`Listening to ${channel}`)
window.Echo.private(`${channel}`)
    .notification((notification) => {
      switch (notification?.type) {
        case 'App\\Notifications\\AccountDisabledNotification':
          router.post('/logout')
          break;
        default:
          break;
      }
    });
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
