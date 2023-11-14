<script setup>
import {useForm} from "@inertiajs/vue3";
import {computed, reactive} from "vue";
import {useAlertStore} from "@/Stores/AlertStore.js";
import Checkbox from "@/Components/Common/Forms/Checkbox.vue";

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

const marketPlanOptions = [
  {
    name: 'Traditional',
    value: 'traditional'
  },
  {
    name: 'Binary',
    value: 'binary'
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
            Available Services
          </h3>
        </v-list-item-title>
        <v-list-item-subtitle>
          Please determine which services should be enabled for the Tenant.
        </v-list-item-subtitle>
      </v-list-item>
    </v-col>
    <v-col cols="12" md="9">
      <v-form @submit.prevent="onSubmitHandler" :disabled="form.processing || disabled">
        <v-card elevation="0" border :loading="form.processing">
          <template v-slot:loader>
            <v-progress-linear v-show="form.processing" color="primary" indeterminate/>
          </template>
          <v-card-text>
            <Checkbox
                v-model="form.has_airline_ticketing"
                label="Airline Ticketing *"
                name="has_airline_ticketing"
                :form="form"
                autofocus
                type="has_airline_ticketing"
            />
            <Checkbox
                v-model="form.has_eloading"
                label="E-Loading *"
                name="has_eloading"
                :form="form"
                autofocus
                type="has_eloading"
            />
            <Checkbox
                v-model="form.has_insurance"
                label="Insurance *"
                name="has_insurance"
                :form="form"
                autofocus
                type="has_insurance"
            />
            <Checkbox
                v-model="form.has_bills_payment"
                label="Bills Payment *"
                name="has_bills_payment"
                :form="form"
                autofocus
                type="has_bills_payment"
            />
          </v-card-text>
        </v-card>
      </v-form>
    </v-col>
  </v-row>

</template>
