import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";

export function useAuth() {
    const page = usePage();

    const user = computed(() => {
        return page.props.auth?.user ?? {}
    })

    const permissions = computed(() => {
        return page.props.auth?.permissions ?? []
    })

    const can = (permission) => {
        return permissions.value?.includes(permission)
    }

    const canEither = (params) => {
        for (const param of params) {
            if (can(param)) {
                return true
            }
        }

        return false
    }

    return {
        user,
        permissions,
        can,
        canEither
    }
}
