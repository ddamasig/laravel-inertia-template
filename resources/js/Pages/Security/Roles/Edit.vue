<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumbs from "@/Components/Layout/Breadcrumbs.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";
import RoleForm from "@/Components/Roles/RoleForm.vue";

const props = defineProps({
    breadcrumbs: Array,
    errorBags: Object,
    role: Object,
})

</script>

<template>
    <AppLayout>
        <v-sheet style="display: flex; flex-direction: column;">
            <div style="flex-grow: 0">
                <PageHeader title="Edit Role"/>
                <Breadcrumbs :items="props.breadcrumbs"/>
            </div>
            <v-divider class="mt-3"/>

            <v-row class="mt-4">
                <v-col cols="12">
                    <RoleForm :role="props.role"/>
                </v-col>
            </v-row>
        </v-sheet>
    </AppLayout>
</template>
