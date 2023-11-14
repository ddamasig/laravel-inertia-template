<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PermissionForm from "@/Components/Permissions/PermissionForm.vue";

const props = defineProps({
    breadcrumbs: Array,
    errorBags: Object,
    permission: Object,
})

</script>

<template>
    <AppLayout title="View Permission" :breadcrumbs="breadcrumbs">
        <v-sheet style="display: flex; flex-direction: column;">
            <v-row class="mt-4">
                <v-col cols="12">
                    <PermissionForm :permission="props.permission" :disabled="true"/>
                </v-col>
            </v-row>
        </v-sheet>
    </AppLayout>
</template>
