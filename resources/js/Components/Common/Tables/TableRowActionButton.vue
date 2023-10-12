<script setup>
import {mdiDotsVertical} from "@mdi/js";

const props = defineProps({
    items: Array,
});
</script>

<template>
    <v-menu close-on-content-click>
        <template v-slot:activator="{ props }">
            <v-btn
                v-bind="props"
                :icon="mdiDotsVertical"
                variant="text"
                color="muted"
            />
        </template>

        <v-list>
            <v-list-item
                v-for="(item, i) in items"
                :key="i"
                @click="item.action"
            >
                <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>
