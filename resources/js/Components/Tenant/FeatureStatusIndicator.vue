<template>
  <v-avatar
      color="primary"
      variant="flat"
      class="ml-2"
      density="compact"
      size="24"
  >
    <v-icon size="18" :icon="mdiCheck"></v-icon>
  </v-avatar>
</template>

<script setup>
import {mdiCheck} from "@mdi/js";
</script>
