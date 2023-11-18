<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import UserForm from "@/Components/Management/Users/UserForm.vue";

const props = defineProps({
    breadcrumbs: Array,
    permissions: Array,
    provinces: Array,
    roles: Array,
})

</script>

<template>
    <AppLayout title="Create User" :breadcrumbs="breadcrumbs">
        <v-sheet style="display: flex; flex-direction: column;">
            <v-row class="mt-4">
                <v-col cols="12">
                    <UserForm :provinces="provinces" :roles="roles" :permissions="permissions"/>
                </v-col>
            </v-row>
        </v-sheet>
    </AppLayout>
</template>
