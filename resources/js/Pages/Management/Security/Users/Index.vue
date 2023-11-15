<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {router} from "@inertiajs/vue3";
import {reactive} from "vue";
import {mdiMagnify} from "@mdi/js";
import {useServerDataTable} from "@/Composables/useServerDataTable.js";
import {useDisplayDateFormat} from "@/Composables/useDisplayDateFormat.js";
import TableRowActionButton from "@/Components/Common/Tables/TableRowActionButton.vue";
import {useAlertStore} from "@/Stores/AlertStore.js";
import {useConfirmModalStore} from "@/Stores/ConfirmModalStore.js";
import Select from "@/Components/Common/Forms/Select.vue";

const deleteModal = useConfirmModalStore()
const alert = useAlertStore()

const statusOptions = [{
    text: 'Show All',
    value: 'IGNORE'
}, {
    text: 'Show Active Only',
    value: 'active'
}, {
    text: 'Show Inactive Only',
    value: 'inactive'
}]

const {
    loading,
    refetch,
    queries
} = useServerDataTable({
    url: '/management/users',
    only: ['resources'],
    filters: {
        'filter[status]': statusOptions[0].value
    }
})

const state = reactive({
    headers: [
        {title: 'Full Name', key: 'full_name', align: 'start'},
        {title: 'E-mail', key: 'email', align: 'start'},
        {title: 'Mobile Number', key: 'mobile_number', align: 'start'},
        {title: 'Status', key: 'status', align: 'start'},
        {title: 'Date Added', key: 'created_at', align: 'start'},
        {title: '', key: 'action', align: 'end', sortable: false},
    ],
    filters: {
        search: ''
    }
})

const props = defineProps({
    breadcrumbs: Array,
    resources: Object,
})

const getTableRowActions = (row) => {
    return [
        {
            title: 'Edit',
            action: () => router.get(`/management/users/${row.id}/edit`)
        },
        {
            title: 'Delete',
            action: () => deleteModal.open({
                title: 'Confirm Deletion',
                content: "Please note that by deleting this user, you are also revoking some users' abilities to perform certain actions in the system. Do you want to proceed?",
                onConfirm: () => {
                    router.delete(`/management/users/${row.id}`, {
                        onSuccess: () => alert.success('Deleted', `User ${row.name} has been successfully deleted.`),
                        onError: () => alert.success('Error', `Failed to delete User ${row.name}`),
                        onFinish: () => deleteModal.close()
                    })
                },
            })
        },
    ]
}
</script>

<template>
    <AppLayout title="Manage Users" :breadcrumbs="breadcrumbs">
        <v-sheet elevation="0" border class="pa-4 mt-8">
            <v-data-table-server
                fixed-header
                v-model:items-per-page="queries.per_page"
                v-model:page="queries.page"
                v-model:sort-by="queries.sort"
                :items-length="props.resources.total"
                :headers="state.headers"
                :items="props.resources.data ?? []"
                class="elevation-0"
                item-value="id"
                :loading="loading"
            >
                <template v-slot:top>
                    <v-toolbar color="transparent">
                        <v-btn
                            variant="flat"
                            color="primary"
                            min-height="42"
                            @click="router.get('/management/users/create')"
                        >
                            New
                        </v-btn>
                        <v-divider vertical class="ml-4"/>
                        <v-toolbar-title>
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        v-model="queries['filter[search]']"
                                        @keydown.enter="refetch"
                                        label="Type your keywords then press Enter"
                                        variant="outlined"
                                        color="primary"
                                        single-line
                                        :prepend-inner-icon="mdiMagnify"
                                        density="compact"
                                        hide-details
                                        style="flex-grow: 6"
                                    />
                                </v-col>
                                <v-col lg="3">
                                    <Select
                                        v-model="queries['filter[status]']"
                                        :items="statusOptions"
                                        label="Status"
                                        density="compact"
                                        hide-details
                                        item-value="value"
                                        item-title="text"
                                        single-line
                                    />
                                </v-col>
                            </v-row>
                        </v-toolbar-title>
                    </v-toolbar>
                </template>

                <template v-slot:[`item.full_name`]="{ item }">
                    <span
                        class="text-primary font-weight-bold cursor-pointer"
                        @click="router.get(`/management/users/${item.id}`)"
                    >
                        {{ item.full_name }}
                    </span>
                </template>

                <template v-slot:[`item.status`]="{ item }">
                    <span class="text-capitalize">
                        {{ item.status }}
                    </span>
                </template>

                <template v-slot:[`item.created_at`]="{ item }">
                    {{ useDisplayDateFormat(item.created_at) }}
                </template>
                <template v-slot:[`item.action`]="{item}">
                    <TableRowActionButton :items="getTableRowActions(item)"/>
                </template>
            </v-data-table-server>
        </v-sheet>
    </AppLayout>
</template>
