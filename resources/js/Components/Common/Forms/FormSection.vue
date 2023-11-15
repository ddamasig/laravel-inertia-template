<template>
  <v-row>
    <v-col cols="12" md="3">
      <v-list-item>
        <v-list-item-title>
          <h3>
            {{ title }}
          </h3>
        </v-list-item-title>
        <v-list-item-subtitle>
          {{ subtitle }}
        </v-list-item-subtitle>
      </v-list-item>
    </v-col>
    <v-col cols="12" md="9">
      <v-card elevation="0" border :loading="loading">
        <template v-slot:loader>
          <v-progress-linear v-show="loading" color="primary" indeterminate/>
        </template>
        <v-card-text>
          <slot></slot>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script setup>
const props = defineProps({
  title: String,
  subtitle: String,
  loading: Boolean,
})
</script>
