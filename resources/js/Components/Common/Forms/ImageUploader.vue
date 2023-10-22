<script setup>
import {computed, reactive, ref} from "vue";
import {mdiAccount, mdiImage} from "@mdi/js";
import {useSingleImageUpload} from "@/Composables/useSingleImageUpload.js";
import {useFileSize} from "@/Composables/useFileSize.js";

const props = defineProps({
    modelValue: String | Number,
    form: Object,
    name: String,
    label: String,
    progress: String | Number,
    loading: String | Boolean,
})

const emit = defineEmits(['update:modelValue'])

const uploaderRef = ref(null)
const {format} = useFileSize()
const {onSelectFile, imageData, imagePreview} = useSingleImageUpload()

const value = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})

const errorMessage = computed({
    get() {
        const errors = props.form.errors[props.name]
        if (errors) {
            return errors
        }
        return null
    },
})

const title = computed({
    get() {
        const data = imageData?.value
        let size = format(data?.size)
        size = size ? `, ${size}` : ''
        if (data) {
            return `${data.name}${size}`
        }
        return 'No image uploaded yet'
    },
})

const onInput = () => {
    if (!!props.form) {
        props.form.clearErrors(props.name)
    }
}

const onTriggerUploader = () => {
    uploaderRef.value.click()
}

</script>

<template>
    <v-list-item class="py-7 px-0" lines="three">
        <template #prepend>
            <v-avatar
                color="grey"
                size="64"
                rounded
                :image="imagePreview"
            >
                <v-icon size="32">{{ mdiImage }}</v-icon>
            </v-avatar>
        </template>
        <v-list-item-title>
            <span class="font-weight-bold">Avatar</span>
            | {{ title }}
        </v-list-item-title>
        <v-list-item-title>
            <v-btn
                @click.prevent="onTriggerUploader"
                variant="flat"
                color="primary"
                density="compact"
            >
                Upload
            </v-btn>
            <v-file-input
                v-show="false"
                v-model="value"
                @input="onInput"
                @change="onSelectFile"
                ref="uploaderRef"
                :label="props.label"
                variant="outlined"
                density="compact"
                color="primary"
                hide-details
                prepend-icon=""
                single-line
                accept="image/*"
                :error="!!errorMessage"
                :error-messages="errorMessage"
            />
        </v-list-item-title>
        <v-list-item-subtitle class="py-2">
            <v-progress-linear v-if="loading" color="primary" model-value="progress ?? 0"/>
            <span v-if="errorMessage" class="text-error">
                {{ errorMessage }}
            </span>
        </v-list-item-subtitle>
    </v-list-item>
</template>
