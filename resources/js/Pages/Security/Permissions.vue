<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {router, usePage} from "@inertiajs/vue3";
import {computed, reactive, ref, watch} from "vue";
import debounce from 'debounce'
import Breadcrumbs from "@/Components/Layout/Breadcrumbs.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";
import {mdiMagnify, mdiDotsVertical} from "@mdi/js";
import {useServerDataTable} from "@/Composables/useServerDataTable.js";
import {useDisplayDateFormat} from "../../Composables/useDisplayDateFormat.js";

// TODO: convert this to a composable and add support for dynamically adding filters

const {
    loading,
    refetch,
    queries
} = useServerDataTable({
    url: '/permissions',
    only: ['paginated'],
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
    paginated: Object
})


const page = usePage()
const user = computed(() => page.props.auth.user)
</script>

<template>
    <AppLayout>
        <v-sheet style="display: flex; flex-direction: column;">

            <div style="flex-grow: 0">
                <Breadcrumbs :items="props.breadcrumbs"/>
                <PageHeader title="Permissions"/>
            </div>

            <v-row class="mt-4" style="flex-grow: 4">
                <v-col cols="12">
                    <v-sheet border class="pa-2">
                        <v-data-table-server
                            fixed-header
                            v-model:items-per-page="queries.per_page"
                            v-model:page="queries.page"
                            v-model:sort-by="queries.sort"
                            :items-length="props.paginated.total"
                            :headers="state.headers"
                            :items="props.paginated.data ?? []"
                            class="elevation-0"
                            item-value="id"
                        >
                            <template v-slot:loader>
                                <v-progress-linear
                                    color="primary"
                                    :active="true"
                                    indeterminate
                                />
                            </template>
                            <template v-slot:[`item.created_at`]="{ item }">
                                {{ useDisplayDateFormat(item.created_at) }}
                            </template>
                            <template v-slot:[`item.action`]>
                                <v-btn :icon="mdiDotsVertical" variant="text" color="muted"/>
                            </template>
                            <template v-slot:top>
                                <v-toolbar color="transparent">
                                    <v-btn variant="flat" color="primary" min-height="42">New</v-btn>
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
                </v-col>
            </v-row>
        </v-sheet>
    </AppLayout>
</template>
