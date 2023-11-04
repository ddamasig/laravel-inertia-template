import {defineStore} from "pinia";
import {ref, watch} from "vue";

export const useAlertStore = defineStore('alert', () => {
    const alerts = ref([]);

    function unshift(title, content, type) {
        alerts.value.unshift({
            title,
            content,
            type,
        })
        setTimeout(() => {
            pop()
        }, 5000)
    }

    function success(title, content) {
        unshift(title, content, 'success')
    }

    function error(title, content) {
        unshift(title, content, 'error')
    }

    function handleError(error) {
        if (error?.custom) {
            unshift('Error', error?.custom, 'error')
        }
    }

    function info(title, content) {
        unshift(title, content, 'info')
    }

    function pop() {
        alerts.value.pop()
    }

    function clear() {
        alerts.value = []
    }

    return {
        alerts,
        pop,
        unshift,
        success,
        error,
        handleError,
        info,
        clear,
    }
})
