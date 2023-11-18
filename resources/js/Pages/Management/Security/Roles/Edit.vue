<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import RoleForm from "@/Components/Common/Roles/RoleForm.vue";

const props = defineProps({
    breadcrumbs: Array,
    permissions: Array,
    errorBags: Object,
    role: Object,
})

</script>

<template>
    <AppLayout title="Edit Role" :breadcrumbs="breadcrumbs">
        <v-sheet style="display: flex; flex-direction: column;">
            <v-row class="mt-4">
                <v-col cols="12">
                    <RoleForm :role="props.role" :permissions="permissions"/>
                </v-col>
            </v-row>
        </v-sheet>
    </AppLayout>
</template>
