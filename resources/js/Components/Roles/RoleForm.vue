<script setup>
import {router, useForm} from "@inertiajs/vue3";
import {reactive} from "vue";
import {useAlertStore} from "@/Stores/AlertStore.js";
import PermissionFormSelectorItem from "@/Components/Permissions/PermissionFormSelector/PermissionFormSelectorItem.vue";
import {mdiMagnify} from "@mdi/js";
import PermissionFormSelector from "@/Components/Permissions/PermissionFormSelector/PermissionFormSelector.vue";

const alert = useAlertStore()
const props = defineProps({
    role: Object,
    permissions: Array,
    disabled: Boolean
})

const form = useForm({
    name: props.role?.name ?? null,
    description: props.role?.description ?? null,
    permissions: props.role?.permissions ?? []
})

const state = reactive({
    loading: false
})

const onSubmitHandler = () => {
    const action = props.role ? 'update' : 'create';
    const options = {
        preserveState: true,
        onStart: () => {
            form.processing = true
        },
        onError: () => {
            alert.error('Error', `Failed to ${action} role ${form.name}.`)
        },
        onSuccess: () => {
            alert.success('Success', `Successfully ${action}d role ${form.name}.`)
        },
        onFinish: () => form.processing = false,
    }

    const transformFunction = (data) => ({
        ...data,
        permissions: data.permissions.map(p => p.name)
    })

    if (props.disabled) {
        return
    }

    if (props.role) {
        form.transform(transformFunction).put(`/roles/${props.role.id}`, options)
        return
    }

    form.transform(transformFunction).post('/roles', options)
}
</script>

<template>
    <v-form @submit.prevent="onSubmitHandler" :disabled="form.processing">
        <v-card elevation="0" border :loading="form.processing">
            <template v-slot:loader>
                <v-progress-linear color="primary" indeterminate v-show="form.processing"/>
            </template>
            <v-card-text class="pt-2">
                <v-row>
                    <v-col cols="12" lg="6">
                        <h2 class="d-block v-card-title px-0">
                            Basic Information
                        </h2>
                        <v-text-field
                            v-model="form.name"
                            label="Name"
                            variant="outlined"
                            density="compact"
                            color="primary"
                            class="mb-4"
                            autofocus
                            @input="form.clearErrors('name')"
                            :error="!!form.errors.name"
                            :error-messages="form.errors.name"
                            :readonly="disabled"
                        />
                        <v-textarea
                            v-model="form.description"
                            label="Description"
                            variant="outlined"
                            density="compact"
                            color="primary"
                            auto-grow
                            @input="form.clearErrors('description')"
                            :error="!!form.errors.description"
                            :error-messages="form.errors.description"
                            :readonly="disabled"
                        />
                    </v-col>
                    <v-divider vertical class="d-none d-lg-block"/>
                    <v-col cols="12" lg="6">
                        <PermissionFormSelector
                            v-model="form.permissions"
                            :permissions="permissions"
                            :disabled="disabled"
                        />
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions
                class="px-4 border-t"
            >
                <v-spacer/>
                <v-btn
                    v-if="disabled"
                    type="submit"
                    variant="flat"
                    color="primary"
                    min-width="120px"
                    :loading="form.processing"
                    @click="router.get(`/roles/${role.id}/edit`)"
                >
                    Edit
                </v-btn>
                <v-btn
                    v-if="!disabled"
                    type="submit"
                    variant="flat"
                    color="primary"
                    min-width="120px"
                    :loading="form.processing"
                    :disabled="!form.isDirty"
                >
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>
