import {ref} from "vue";

export function useSingleImageUpload() {
    const imageData = ref(null)
    const imagePreview = ref(null)

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
