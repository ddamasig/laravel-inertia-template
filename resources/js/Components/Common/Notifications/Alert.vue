<script setup>
import {watch} from "vue";
import {useAlertStore} from "@/Stores/AlertStore.js";

const {alerts, pop, clear} = useAlertStore()

</script>

<template>
    <div class="v-alert--floating">
        <v-alert
            v-for="({title, content, type}, index) in alerts"
            :key="index"
            :model-value="true"
            :color="type"
            :type="type"
            class="mb-3"
            elevation="0"
            style="z-index: 999999"
            max-width="400"
            closable
        >
            <v-alert-title>{{ title }}</v-alert-title>
            {{ content }}
        </v-alert>
    </div>
</template>

<style scoped>

.v-alert--floating {
    position: absolute;
    margin-top: 10px;
    right: 24px
}
</style>
