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
        name: 'Frozen Yogurt',
        calories: 159,
        fat: 6.0,
        carbs: 24,
        protein: 4.0,
        iron: '1',
    },
    {
        name: 'Jelly bean',
        calories: 375,
        fat: 0.0,
        carbs: 94,
        protein: 0.0,
        iron: '0',
    },
    {
        name: 'KitKat',
        calories: 518,
        fat: 26.0,
        carbs: 65,
        protein: 7,
        iron: '6',
    },
    {
        name: 'Eclair',
        calories: 262,
        fat: 16.0,
        carbs: 23,
        protein: 6.0,
        iron: '7',
    },
    {
        name: 'Gingerbread',
        calories: 356,
        fat: 16.0,
        carbs: 49,
        protein: 3.9,
        iron: '16',
    },
    {
        name: 'Ice cream sandwich',
        calories: 237,
        fat: 9.0,
        carbs: 37,
        protein: 4.3,
        iron: '1',
    },
    {
        name: 'Lollipop',
        calories: 392,
        fat: 0.2,
        carbs: 98,
        protein: 0,
        iron: '2',
    },
    {
        name: 'Cupcake',
        calories: 305,
        fat: 3.7,
        carbs: 67,
        protein: 4.3,
        iron: '8',
    },
    {
        name: 'Honeycomb',
        calories: 408,
        fat: 3.2,
        carbs: 87,
        protein: 6.5,
        iron: '45',
    },
    {
        name: 'Donut',
        calories: 452,
        fat: 25.0,
        carbs: 51,
        protein: 4.9,
        iron: '22',
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
        itemsPerPage: 5,
        headers: [
            {
                title: 'Dessert (100g serving)',
                align: 'start',
                sortable: false,
                key: 'name',
            },
            {title: 'Calories', key: 'calories', align: 'end'},
            {title: 'Fat (g)', key: 'fat', align: 'end'},
            {title: 'Carbs (g)', key: 'carbs', align: 'end'},
            {title: 'Protein (g)', key: 'protein', align: 'end'},
            {title: 'Iron (%)', key: 'iron', align: 'end'},
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

        <v-row>
            <v-col cols="8">
                <PageHeader title="Permissions"/>
            </v-col>
            <v-col class="text-right">
                <v-btn color="primary" elevation="0" size="48" min-width="120">
                    New
                </v-btn>
            </v-col>
        </v-row>

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
                        <template v-slot:top>
                            <v-toolbar color="transparent">
                                <v-btn variant="flat" color="primary" min-height="42">New</v-btn>
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
