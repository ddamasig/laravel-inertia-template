<script setup>
import {router} from "@inertiajs/vue3";


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
          @click="navigate(item)"
      >
        <v-list-item-title>{{ item.title }}</v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>
