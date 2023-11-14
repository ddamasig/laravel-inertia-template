<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PermissionForm from "@/Components/Permissions/PermissionForm.vue";

const props = defineProps({
    breadcrumbs: Array,
})

</script>

<template>
    <AppLayout title="Create Permission" :breadcrumbs="breadcrumbs">
        <v-sheet style="display: flex; flex-direction: column;">
            <v-row class="mt-4">
                <v-col cols="12">
                    <PermissionForm/>
                </v-col>
            </v-row>
        </v-sheet>
    </AppLayout>
</template>
