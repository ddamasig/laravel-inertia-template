<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PermissionForm from "@/Components/Common/Permissions/PermissionForm.vue";

const props = defineProps({
    breadcrumbs: Array,
    errorBags: Object,
    permission: Object,
})

</script>

<template>
    <AppLayout title="Edit Permission" :breadcrumbs="breadcrumbs">
        <v-sheet style="display: flex; flex-direction: column;">
            <v-row class="mt-4">
                <v-col cols="12">
                    <PermissionForm :permission="props.permission"/>
                </v-col>
            </v-row>
        </v-sheet>
    </AppLayout>
</template>
