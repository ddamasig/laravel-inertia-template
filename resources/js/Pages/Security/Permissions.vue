<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {router} from "@inertiajs/vue3";
import {reactive} from "vue";
import debounce from 'debounce'
import Breadcrumbs from "@/Components/Layout/Breadcrumbs.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";
import {mdiMagnify, mdiDotsVertical} from "@mdi/js";

const model = reactive({
    search: ''
})

const props = defineProps({
    breadcrumbs: Array,
    users: Array
})

const getUsers = debounce(() => {
    router.replace(`dashboard?search=${model.search}`, {
        only: ['users']
    })
}, 500)

</script>

<script>
const desserts = [
    {
        name: 'View Users',
        key: 'view:users',
        description: 'Allows the users to view the list of users within the system.',
        created_at: 'October 01, 2023',
    },
    {
        name: 'Create Users',
        key: 'create:users',
        description: 'Allows the user to create new users within the system.',
        created_at: 'October 01, 2023',
    },
    {
        name: 'Update Users',
        key: 'update:users',
        description: 'Allows the user to update existing users within the system.',
        created_at: 'October 01, 2023',
    },
    {
        name: 'Delete Users',
        key: 'delete:users',
        description: 'Allows the user to remove existing users within the system.',
        created_at: 'October 01, 2023',
    },
]

const FakeAPI = {
    async fetch({page, itemsPerPage, sortBy}) {
        return new Promise(resolve => {
            setTimeout(() => {
                const start = (page - 1) * itemsPerPage
                const end = start + itemsPerPage
                const items = desserts.slice()

                if (sortBy.length) {
                    const sortKey = sortBy[0].key
                    const sortOrder = sortBy[0].order
                    items.sort((a, b) => {
                        const aValue = a[sortKey]
                        const bValue = b[sortKey]
                        return sortOrder === 'desc' ? bValue - aValue : aValue - bValue
                    })
                }

                const paginated = items.slice(start, end)

                resolve({items: paginated, total: items.length})
            }, 500)
        })
    },
}

export default {
    data: () => ({
        itemsPerPage: 25,
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
        serverItems: [],
        loading: true,
        totalItems: 0,
    }),
    methods: {
        loadItems({page, itemsPerPage, sortBy}) {
            console.log('Options Updated:')
            console.log(page)
            console.log(itemsPerPage)
            console.log(sortBy)

            this.loading = true
            FakeAPI.fetch({page, itemsPerPage, sortBy}).then(({items, total}) => {
                this.serverItems = items
                this.totalItems = total
                this.loading = false
            })
        },
        onUpdateOptionsHandler(event) {
            console.log('Options Updated:')
            console.log(event)
        }
    },
}
</script>

<template>
    <AppLayout>
        <Breadcrumbs :items="props.breadcrumbs"/>

        <PageHeader title="Permissions"/>

        <v-row class="mt-4">
            <v-col cols="12">
                <v-sheet border class="pa-2">
                    <v-data-table-server
                        v-model:items-per-page="itemsPerPage"
                        :headers="headers"
                        :items-length="totalItems"
                        :items="serverItems"
                        :loading="loading"
                        class="elevation-0"
                        item-value="name"
                        @update:options="loadItems"
                    >
                        <template v-slot:[`item.action`]>
                            <v-btn :icon="mdiDotsVertical" variant="text" color="muted"/>
                        </template>
                        <template v-slot:top>
                            <v-toolbar color="transparent">
                                <v-btn variant="flat" color="primary" min-height="42">New</v-btn>
                                <v-divider vertical class="ml-4"/>
                                <v-toolbar-title>
                                    <v-text-field
                                        label="Search"
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
    </AppLayout>
</template>
