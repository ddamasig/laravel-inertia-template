<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
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
    <AppLayout title="Edit User" :breadcrumbs="breadcrumbs">
        <v-sheet style="display: flex; flex-direction: column;">
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
