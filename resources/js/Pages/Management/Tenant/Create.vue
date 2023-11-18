<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {useForm} from "@inertiajs/vue3";
import {reactive} from "vue";
import TenantForm from "@/Components/Management/Tenants/TenantForm.vue";

const props = defineProps({
  breadcrumbs: Array,
  provinces: Array,
  tenant: Object,
})

const form = useForm({
  name: props.tenant?.name ?? null,
  domain: props.tenant?.domain ?? null,
  contact_person: props.tenant?.contact_person ?? null,
  email: props.tenant?.email ?? null,
  mobile_number: props.tenant?.mobile_number ?? null,
  province: props.tenant?.province ?? null,
  municipality: props.tenant?.municipality ?? null,
  additional_address_information: props.tenant?.additional_address_information ?? null,
  status: props.tenant?.status ?? 'active',
  market_plan: props.tenant?.market_plan ?? null,
  max_sub_accounts: props.tenant?.max_sub_accounts ?? null,
  account_upgrades_enabled: props.tenant?.account_upgrades_enabled ?? null,
  account_levels: props.tenant?.account_levels ?? [{name: 'Silver', cost: 2000}],
});

const state = reactive({
  loading: false
})

const onSubmitHandler = () => {
  const action = props.tenant ? 'update' : 'create';
  const options = {
    preserveState: true,
    onStart: () => {
      form.processing = true
    },
    onError: (error) => {
      alert.error('Error', `Failed to ${action} tenant ${form.name}.`)
      alert.handleError(error)
    },
    onSuccess: () => {
      alert.success('Success', `Successfully ${action}d tenant ${form.name}.`)
    },
    onFinish: () => form.processing = false,
  }

  const transformFunction = (data) => ({
    name: form.name,
    domain: form.domain,
    contact_person: form.contact_person,
    email: form.email,
    mobile_number: form.mobile_number,
    status: form.status ?? null,
    province_id: form.province?.id ?? null,
    municipality_id: form.municipality?.id ?? null,
    market_plan: form.market_plan ?? null,
    max_sub_accounts: form.max_sub_accounts?.id ?? 7,
    account_upgrades_enabled: form.account_upgrades_enabled?.id ?? false,
    account_levels: form.account_levels ?? null,
  })

  if (props.disabled) {
    return
  }

  if (props.user) {
    form.transform(transformFunction).post(`/management/users/${props.user.id}`, options)
    return
  }

  form.transform(transformFunction).post('/users', options)
}

const disabled = false;

</script>

<template>
  <AppLayout title="Create Tenant" :breadcrumbs="breadcrumbs">
    <TenantForm
        :tenant="tenant"
        :provinces="provinces"
    />
  </AppLayout>
</template>
