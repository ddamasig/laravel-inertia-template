import {defineStore} from "pinia";
import {reactive, ref} from "vue";

export const useConfirmModalStore = defineStore('confirmModal', () => {
    const isOpen = ref(false);
    const title = ref('Confirm Action');
    const content = ref('Are you sure you want to proceed with this action?');
    const onConfirm = ref(() => {
    });
    const onCancel = ref(() => {
    });
    const isLoading = ref(false);
    const closeOnFinish = ref(true);

    function open(params) {
        title.value = params.title
        content.value = params.content
        onConfirm.value = params.onConfirm
        onCancel.value = params.onCancel
        isLoading.value = false
        isOpen.value = true
    }

    function close() {
        title.value = null
        content.value = null
        onConfirm.value = () => {
        }
        onCancel.value = () => {
        }
        isOpen.value = false
        isLoading.value = false
        console.log('Closing!')
    }

    function startLoading() {
        isLoading.value = true
    }

    function stopLoading() {
        isLoading.value = false
    }

    async function confirm() {
        startLoading()
        await onConfirm.value()
        console.log('Confirmation Done!')
    }

    async function cancel() {
        stopLoading()
        close()
    }

    return {
        isOpen,
        isLoading,
        startLoading,
        stopLoading,
        open,
        close,
        title,
        content,
        onConfirm,
        onCancel,
        confirm,
        cancel
    }
})
