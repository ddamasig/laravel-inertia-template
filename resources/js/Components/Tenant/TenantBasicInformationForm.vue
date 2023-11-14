<script setup>
import {router, useForm} from "@inertiajs/vue3";
import {computed, reactive} from "vue";
import {useAlertStore} from "@/Stores/AlertStore.js";
import TextField from "@/Components/Common/Forms/TextField.vue";
import SearchableSelect from "@/Components/Common/Forms/SearchableSelect.vue";
import Select from "@/Components/Common/Forms/Select.vue";
import ImageUploader from "@/Components/Common/Forms/ImageUploader.vue";
import PermissionQuickList from "@/Components/Permissions/PermissionQuickList.vue";
import SwitchField from "@/Components/Common/Forms/SwitchField.vue";

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
        status: form.status ?? null,
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

const statusOptions = [
    {
        name: 'Active',
        value: 'active'
    },
    {
        name: 'Inactive',
        value: 'inactive'
    },
]

const onEditMode = computed(() => {
    return !!props.user?.id
})
</script>

<template>
    <v-row>
        <v-col cols="12" md="3">
            <v-list-item>
                <v-list-item-title>
                    <h3>
                        Basic Information
                    </h3>
                </v-list-item-title>
                <v-list-item-subtitle>
                    Please tell us about your company.
                </v-list-item-subtitle>
            </v-list-item>
        </v-col>
        <v-col cols="12" md="9">
            <v-form @submit.prevent="onSubmitHandler" :disabled="form.processing || disabled">
                <v-card elevation="0" border :loading="form.processing">
                    <template v-slot:loader>
                        <v-progress-linear v-show="form.processing" color="primary" indeterminate/>
                    </template>
                    <v-card-text class="pt-4">
                        <TextField
                            v-model="form.name"
                            label="Name *"
                            name="name"
                            :form="form"
                            autofocus
                        />
                        <TextField
                            v-model="form.contact_person"
                            label="Contact Person *"
                            name="contact_person"
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
                        <TextField
                            v-model="form.additional_address_information"
                            label="Additional Address Information"
                            name="additional_address_information"
                            :form="form"
                        />
                    </v-card-text>
                </v-card>
            </v-form>
        </v-col>
    </v-row>

</template>
