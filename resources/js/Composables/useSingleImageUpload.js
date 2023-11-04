import {ref} from "vue";

export function useSingleImageUpload({data, preview}) {
    const imageData = ref(data ?? null)
    const imagePreview = ref(preview ?? null)

    /**
     * This function should be bound to a v-file-input's @change event.
     * @param event
     */
    const onSelectFile = (event) => {
        const input = event.target;
        if (input.files) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.value = e.target.result;
            }
            imageData.value = input.files[0];
            reader.readAsDataURL(input.files[0]);
        }
    }

    return {
        imageData,
        imagePreview,
        onSelectFile
    }
}
