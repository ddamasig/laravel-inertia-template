<script setup>
import {router, useForm} from "@inertiajs/vue3";
import {reactive} from "vue";
import {useAlertStore} from "@/Stores/AlertStore.js";
import PermissionFormSelectorItem from "@/Components/Permissions/PermissionFormSelector/PermissionFormSelectorItem.vue";
import {mdiMagnify} from "@mdi/js";
import PermissionFormSelector from "@/Components/Permissions/PermissionFormSelector/PermissionFormSelector.vue";
import TextField from "@/Components/Common/Forms/TextField.vue";
import SearchableSelect from "@/Components/Common/Forms/SearchableSelect.vue";
import Checkbox from "@/Components/Common/Forms/Checkbox.vue";
import Select from "@/Components/Common/Forms/Select.vue";
import ImageUploader from "@/Components/Common/Forms/ImageUploader.vue";

const alert = useAlertStore()
const props = defineProps({
    user: Object,
    roles: Array,
    permissions: Array,
    provinces: Array,
    disabled: Boolean
})

const form = useForm({
    avatar: props.user?.avatar ?? null,
    first_name: props.user?.first_name ?? null,
    middle_name: props.user?.middle_name ?? null,
    last_name: props.user?.last_name ?? null,
    email: props.user?.email ?? null,
    mobile_number: props.user?.mobile_number ?? null,
    status: props.user?.status ?? null,
    role: props.user?.role ?? null,
    permissions: props.user?.permissions ?? [],
    username: props.user?.username ?? null,
    password: props.user?.password ?? null,
    password_confirmation: null,
    password_mode: 'temporary',
    province: props.user?.province ?? null,
    municipality: props.user?.municipality ?? null,
})

const state = reactive({
    loading: false
})

const onSubmitHandler = () => {
    const action = props.user ? 'update' : 'create';
    const options = {
        preserveState: true,
        onStart: () => {
            form.processing = true
        },
        onError: () => {
            alert.error('Error', `Failed to ${action} user ${form.name}.`)
        },
        onSuccess: () => {
            alert.success('Success', `Successfully ${action}d user ${form.name}.`)
        },
        onFinish: () => form.processing = false,
    }

    const transformFunction = (data) => ({
        ...data,
        province_id: form.province?.id ?? null,
        municipality_id: form.municipality?.id ?? null,
        avatar: form.avatar?.length > 0 ? form.avatar[0] : null,
        permissions: data.permissions.map(p => p.name)
    })

    if (props.disabled) {
        return
    }

    if (props.user) {
        form.transform(transformFunction).put(`/users/${props.user.id}`, options)
        return
    }

    form.transform(transformFunction).post('/users', options)
}

/**
 * Clear the form.municipality if the province is updated.
 */
const onChangeProvince = () => {
    form.municipality = null
}

const passwordModes = [
    {
        name: 'Temporary Password',
        value: 'temporary'
    },
    {
        name: 'Set Manually',
        value: 'manual'
    },
]
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
                        <ImageUploader
                            v-model="form.avatar"
                            label="Avatar"
                            name="avatar"
                            :form="form"
                        />
                        <TextField
                            v-model="form.first_name"
                            label="First Name *"
                            name="first_name"
                            :form="form"
                            autofocus
                        />
                        <TextField
                            v-model="form.middle_name"
                            label="Middle Name"
                            name="middle_name"
                            :form="form"
                        />
                        <TextField
                            v-model="form.last_name"
                            label="Last Name *"
                            name="last_name"
                            :form="form"
                        />
                        <TextField
                            v-model="form.email"
                            label="E-mail Address *"
                            name="email"
                            :form="form"
                        />
                        <TextField
                            v-model="form.mobile_number"
                            label="Mobile Number *"
                            name="mobile_number"
                            :form="form"
                        />
                        <SearchableSelect
                            v-model="form.province"
                            label="Province *"
                            name="province_id"
                            :form="form"
                            :items="provinces"
                            item-title="name"
                            return-object
                            @update:modelValue="onChangeProvince"
                        />
                        <SearchableSelect
                            v-model="form.municipality"
                            label="Municipality *"
                            name="municipality_id"
                            :form="form"
                            :items="form.province?.municipalities ?? []"
                            item-title="name"
                            :disabled="!form.province"
                            return-object
                        />
                        <h2 class="d-block v-card-title px-0">
                            Credentials
                        </h2>
                        <TextField
                            v-model="form.username"
                            label="Username *"
                            name="username"
                            :form="form"
                            autofocus
                        />
                        <Select
                            v-model="form.password_mode"
                            label="How to Set Password? *"
                            name="password_mode"
                            :form="form"
                            :items="passwordModes"
                            item-title="name"
                            item-value="value"
                        />
                        <template v-if="form.password_mode === 'manual'">
                            <TextField
                                v-model="form.password"
                                label="Password *"
                                name="password"
                                :form="form"
                                type="password"
                            />
                            <TextField
                                v-model="form.password_confirmation"
                                label="Confirm Password *"
                                name="password_confirmation"
                                :form="form"
                                type="password"
                            />
                        </template>

                        <template v-if="form.password_mode === 'temporary'">
                            <v-alert type="info">
                                <v-alert-title>Generate Temporary Password</v-alert-title>
                                The user will receive an email containing the temporary password which can be used to
                                login into the
                                app.
                            </v-alert>
                        </template>
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
            <v-card-actions class="px-4 border-t">
                <v-spacer/>
                <v-btn
                    v-if="disabled"
                    type="submit"
                    variant="flat"
                    color="primary"
                    min-width="120px"
                    :loading="form.processing"
                    @click="router.get(`/users/${user.id}/edit`)"
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
