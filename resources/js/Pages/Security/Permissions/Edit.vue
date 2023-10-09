<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumbs from "@/Components/Layout/Breadcrumbs.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";
import PermissionForm from "@/Components/Permissions/PermissionForm.vue";

const props = defineProps({
    breadcrumbs: Array,
    errorBags: Object,
    permission: Object,
})

</script>

<template>
    <AppLayout>
        <v-sheet style="display: flex; flex-direction: column;">
            <div style="flex-grow: 0">
                <PageHeader title="Edit Permission"/>
                <Breadcrumbs :items="props.breadcrumbs"/>
            </div>
            <v-divider class="mt-3"/>

            <v-row class="mt-4">
                <v-col cols="12">
                    <PermissionForm :permission="props.permission"/>
                </v-col>
            </v-row>
        </v-sheet>
    </AppLayout>
</template>
