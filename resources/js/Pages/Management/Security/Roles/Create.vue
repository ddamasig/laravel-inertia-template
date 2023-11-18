<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import RoleForm from "@/Components/Common/Roles/RoleForm.vue";

const props = defineProps({
    breadcrumbs: Array,
    permissions: Array,
})

</script>

<template>
    <AppLayout title="Create Role" :breadcrumbs="breadcrumbs">
        <v-sheet style="display: flex; flex-direction: column;">
            <v-row class="mt-4">
                <v-col cols="12">
                    <RoleForm :permissions="permissions"/>
                </v-col>
            </v-row>
        </v-sheet>
    </AppLayout>
</template>
