<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumbs from "@/Components/Layout/Breadcrumbs.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";
import UserForm from "@/Components/Management/Users/UserForm.vue";
import TenantForm from "@/Components/Management/Tenants/TenantForm.vue";

const props = defineProps({
    breadcrumbs: Array,
    errorBags: Object,
    permissions: Array,
    user: Object,
    tenant: Object,
    roles: Array,
    provinces: Array,
})

</script>

<template>
    <AppLayout title="View Tenant" :breadcrumbs="breadcrumbs">
        <TenantForm
            :provinces="provinces"
            :permissions="permissions"
            :tenant="tenant"
            disabled
        />
        <UserForm
            :provinces="provinces"
            :roles="roles"
            :permissions="permissions"
            :user="user"
            disabled
        />
    </AppLayout>
</template>
