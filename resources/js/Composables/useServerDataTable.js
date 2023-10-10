import {defineProps, reactive, ref, watch} from "vue";
import debounce from "debounce";
import {router} from "@inertiajs/vue3";

export function useServerDataTable({url, only, filters, paginated}) {

    const loading = ref(false)
    const queries = reactive({
        page: paginated?.current_page ?? 1,
        per_page: paginated?.per_page ?? 100,
        sort: [],
        ...filters,
        ...route().query
    })

    const refetch = debounce((event) => {
        loading.value = true

        const params = {...queries}

        if (queries.sort.length > 0) {
            const order = params.sort[0]
            const direction = order.order === 'desc' ? '-' : ''
            params.sort = direction + order.key
        }
        const qs = new URLSearchParams(params).toString()
        try {
            router.get(`${url}?${qs}`, {
                only,
            }, {
                preserveState: true,
                onFinish: () => loading.value = false
            })
        } catch (error) {
            console.error(error)
        }
    }, 300)

    watch(queries, () => {
        refetch()
    }, {deep: true})

    return {
        refetch,
        loading,
        queries,
    }

}
