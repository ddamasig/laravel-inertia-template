<script setup>
import {router, useForm} from "@inertiajs/vue3";
import {computed, reactive} from "vue";
import {useAlertStore} from "@/Stores/AlertStore.js";
import {mdiMagnify} from "@mdi/js";

const alert = useAlertStore()
const props = defineProps({
    modelValue: Array,
    disabled: Boolean,
    permissions: Object,
})

const emit = defineEmits(['update:modelValue'])

const value = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})
</script>

<template>
    <h2 class="d-block v-card-title px-0">
        Permissions
        <v-badge color="primary" :content="props.modelValue?.length ?? 0" inline>
            &nbsp;
        </v-badge>
    </h2>
    <v-text-field
        label="Type your keywords here"
        :prepend-inner-icon="mdiMagnify"
        variant="outlined"
        density="compact"
        hide-details
        single-line
        color="primary"
    />
    <v-list lines="two" style="max-height: 600px; overflow-y: scroll">
        <v-list-item
            v-for="(permission, index) in permissions"
            :key="index"
            class="border-b"
        >
            <template v-slot:prepend="{ isActive }">
                <v-list-item-action start>
                    <v-checkbox-btn
                        v-model="value"
                        :value="permission"
                        :value-comparator="(value, option) => value.id === option.id"
                        multiple
                        :disabled="disabled"
                        color="primary"
                    />
                </v-list-item-action>
            </template>
            <v-list-item-title>{{ permission.name }}</v-list-item-title>
            <v-list-item-subtitle>{{ permission.description }}</v-list-item-subtitle>
        </v-list-item>
    </v-list>
</template>
