<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {router} from "@inertiajs/vue3";
import {reactive, ref} from "vue";
import Breadcrumbs from "@/Components/Layout/Breadcrumbs.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";
import {mdiMagnify} from "@mdi/js";
import {useServerDataTable} from "@/Composables/useServerDataTable.js";
import {useDisplayDateFormat} from "@/Composables/useDisplayDateFormat.js";
import TableRowActionButton from "@/Components/Common/Tables/TableRowActionButton.vue";
import {useAlertStore} from "@/Stores/AlertStore.js";
import {useConfirmModalStore} from "@/Stores/ConfirmModalStore.js";

const deleteModal = useConfirmModalStore()
const selectedRow = ref(null)
const alert = useAlertStore()

const {
    loading,
    refetch,
    queries
} = useServerDataTable({
    url: '/permissions',
    only: ['resources'],
})

const state = reactive({
    headers: [
        {
            title: 'Name',
            align: 'start',
            key: 'name',
        },
        {title: 'Description', key: 'description', align: 'start'},
        {title: 'Date Added', key: 'created_at', align: 'start'},
        {title: '', key: 'action', align: 'end', sortable: false},
    ],
    filters: {
        search: ''
    }
})

const props = defineProps({
    breadcrumbs: Array,
    resources: Object
})

const getTableRowActions = (row) => {
    return [
        {
            title: 'Delete',
            action: () => deleteModal.open({
                title: 'Confirm Deletion',
                content: "Please note that by deleting this permission, you are also revoking some users' abilities to perform certain actions in the system. Do you want to proceed?",
                onConfirm: () => {
                    router.delete(`/permissions/${row.id}`, {
                        onSuccess: () => alert.success('Deleted', `Permission ${row.name} has been successfully deleted.`),
                        onError: () => alert.success('Error', `Failed to delete Permission ${row.name}`),
                        onFinish: () => deleteModal.close()
                    })
                }
            })
        }
    ]
}

</script>

<template>
    <AppLayout>
        <v-sheet>
            <PageHeader title="Permissions"/>
            <Breadcrumbs :items="props.breadcrumbs"/>
            <v-divider class="mt-3"/>

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
                    <template v-slot:[`item.name`]="{ item }">
                    <span
                        class="text-primary font-weight-bold cursor-pointer"
                        @click="router.get(`permissions/${item.id}`)"
                    >
                        {{ item.name }}
                    </span>
                    </template>
                    <template v-slot:[`item.created_at`]="{ item }">
                        {{ useDisplayDateFormat(item.created_at) }}
                    </template>
                    <template v-slot:[`item.action`]="{item}">
                        <TableRowActionButton :items="getTableRowActions(item)"/>
                    </template>
                    <template v-slot:top>
                        <v-toolbar color="transparent">
                            <v-btn
                                variant="flat"
                                color="primary"
                                min-height="42"
                                @click="router.get('/permissions/create')"
                            >
                                New
                            </v-btn>
                            <v-divider vertical class="ml-4"/>
                            <v-toolbar-title>
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
                                />
                            </v-toolbar-title>
                        </v-toolbar>
                    </template>
                </v-data-table-server>
            </v-sheet>
        </v-sheet>
    </AppLayout>
</template>
