<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumbs from "@/Components/Layout/Breadcrumbs.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";
import RoleForm from "@/Components/Roles/RoleForm.vue";
import UserForm from "@/Components/Users/UserForm.vue";
import TenantBasicInformationForm from "@/Components/Tenant/TenantBasicInformationForm.vue";
import TenantSettingsForm from "@/Components/Tenant/TenantConfigurationForm.vue";
import TenantConfigurationForm from "@/Components/Tenant/TenantConfigurationForm.vue";
import TenantServiceSettings from "@/Components/Tenant/TenantServiceSettings.vue";
import TenantBrandingForm from "@/Components/Tenant/TenantBrandingForm.vue";

const props = defineProps({
    breadcrumbs: Array,
    permissions: Array,
    provinces: Array,
    roles: Array,
})

</script>

<template>
    <AppLayout title="Create Tenant" :breadcrumbs="breadcrumbs">
        <v-sheet style="display: flex; flex-direction: column;">

            <v-row class="mt-4 border-b">
                <v-col cols="12" class="pb-12">
                    <TenantBasicInformationForm
                        :disabled="props.disabled"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-4 border-b">
                <v-col cols="12" class="pb-12">
                    <TenantConfigurationForm
                        :disabled="props.disabled"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-4 border-b">
                <v-col cols="12" class="pb-12">
                    <TenantServiceSettings
                        :disabled="props.disabled"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-4 border-b">
                <v-col cols="12" class="pb-12">
                    <TenantBrandingForm
                        :disabled="props.disabled"
                    />
                </v-col>
            </v-row>
        </v-sheet>
    </AppLayout>
</template>
