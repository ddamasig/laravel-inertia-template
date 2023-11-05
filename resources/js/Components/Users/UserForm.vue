<script setup>
import {router, useForm} from "@inertiajs/vue3";
import {computed, reactive} from "vue";
import {useAlertStore} from "@/Stores/AlertStore.js";
import TextField from "@/Components/Common/Forms/TextField.vue";
import SearchableSelect from "@/Components/Common/Forms/SearchableSelect.vue";
import Select from "@/Components/Common/Forms/Select.vue";
import ImageUploader from "@/Components/Common/Forms/ImageUploader.vue";
import PermissionQuickList from "@/Components/Permissions/PermissionQuickList.vue";

const alert = useAlertStore()
const props = defineProps({
    user: Object,
    roles: Array,
    permissions: Array,
    provinces: Array,
    disabled: Boolean
})

const form = useForm({
    avatar: null,
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
    password_mode: !props.user ? 'manual' : 'unchanged',
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
        onError: (error) => {
            alert.error('Error', `Failed to ${action} user ${form.first_name}.`)
            alert.handleError(error)
        },
        onSuccess: () => {
            alert.success('Success', `Successfully ${action}d user ${form.first_name}.`)
        },
        onFinish: () => form.processing = false,
    }

    const transformFunction = (data) => ({
        first_name: form.first_name,
        middle_name: form.middle_name,
        last_name: form.last_name,
        email: form.email,
        mobile_number: form.mobile_number,
        username: form.username,
        password_mode: form.password_mode,
        password: form.password,
        password_confirmation: form.password_confirmation,
        role_id: form.role?.id ?? null,
        province_id: form.province?.id ?? null,
        municipality_id: form.municipality?.id ?? null,
        avatar: form.avatar?.length > 0 ? form.avatar[0] : null,
    })

    if (props.disabled) {
        return
    }

    if (props.user) {
        form.transform(transformFunction).post(`/users/${props.user.id}`, options)
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

const passwordModes = computed(() => {
    const modes = [
        {
            name: 'Temporary Password',
            value: 'temporary'
        },
        {
            name: 'Set Manually',
            value: 'manual'
        },
    ]

    // If the form is on Edit mode
    if (props.user) {
        modes.unshift({
            name: 'Unchanged',
            value: 'unchanged'
        })
    }

    return modes
})
</script>

<template>
    <v-form @submit.prevent="onSubmitHandler" :disabled="form.processing || disabled">
        <v-card elevation="0" border :loading="form.processing">
            <template v-slot:loader>
                <v-progress-linear v-show="form.processing" color="primary" indeterminate/>
            </template>
            <v-card-text class="pt-2">
                <v-row>
                    <v-col cols="12">
                        <h2 class="d-block v-card-title px-0">
                            Basic Information
                        </h2>
                        <ImageUploader
                            v-model="form.avatar"
                            label="Avatar"
                            name="avatar"
                            :form="form"
                            :default="{
                              data: user?.avatar,
                              preview: user?.avatar?.preview_url
                            }"
                            :disabled="disabled"
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
                            return-object
                            :disabled="!form.province || disabled"
                        />

                        <h2 class="d-block v-card-title px-0">
                            Access Control
                        </h2>
                        <SearchableSelect
                            v-model="form.role"
                            label="Role *"
                            name="role_id"
                            :form="form"
                            :items="roles"
                            item-title="name"
                            return-object
                        />

                        <PermissionQuickList :permissions="form.role?.permissions ?? []"/>

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
