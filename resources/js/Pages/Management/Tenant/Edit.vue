<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumbs from "@/Components/Layout/Breadcrumbs.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";
import UserForm from "@/Components/Management/Users/UserForm.vue";

const props = defineProps({
    breadcrumbs: Array,
    errorBags: Object,
    permissions: Array,
    user: Object,
    roles: Array,
    provinces: Array,
})

</script>

<template>
    <AppLayout>
        <v-sheet style="display: flex; flex-direction: column;">
            <div style="flex-grow: 0">
                <PageHeader title="Edit User"/>
                <Breadcrumbs :items="props.breadcrumbs"/>
            </div>
            <v-divider class="mt-3"/>

            <v-row class="mt-4">
                <v-col cols="12">
                    <UserForm
                        :provinces="provinces"
                        :roles="roles"
                        :permissions="permissions"
                        :user="user"
                    />
                </v-col>
            </v-row>
        </v-sheet>
    </AppLayout>
</template>
