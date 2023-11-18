<script setup>
import TenantBasicInformationForm from "@/Components/Management/Tenants/TenantBasicInformationForm.vue";
import TenantConfigurationForm from "@/Components/Management/Tenants/TenantConfigurationForm.vue";
import TenantServiceSettings from "@/Components/Management/Tenants/TenantServicesForm.vue";
import TenantBrandingForm from "@/Components/Management/Tenants/TenantBrandingForm.vue";
import {router, useForm} from "@inertiajs/vue3";
import {reactive} from "vue";
import {useAlertStore} from "@/Stores/AlertStore.js";

const props = defineProps({
  tenant: Object,
  provinces: Array,
  disabled: Boolean
})

const alert = useAlertStore()

const form = useForm({
  // name: props.tenant?.name ?? null,
  // domain: props.tenant?.domain ?? null,
  // database: props.tenant?.database ?? null,
  // contact_person: props.tenant?.contact_person ?? null,
  // email: props.tenant?.email ?? null,
  // mobile_number: props.tenant?.mobile_number ?? null,
  // province: props.tenant?.province ?? null,
  // municipality: props.tenant?.municipality ?? null,
  // additional_address_information: props.tenant?.additional_address_information ?? null,
  // status: props.tenant?.status ?? 'active',
  // market_plan: props.tenant?.market_plan ?? null,
  // max_sub_accounts: props.tenant?.max_sub_accounts ?? null,
  // account_upgrades_enabled: props.tenant?.account_upgrades_enabled ?? false,
  // can_only_upgrade_once: props.tenant?.can_only_upgrade_once ?? false,
  // account_levels: props.tenant?.account_levels ?? [{name: 'Silver', cost: 2000}],
  // direct_referral_bonus_enabled: props.tenant?.direct_referral_bonus_enabled ?? false,
  // direct_referral_bonus_amount: props.tenant?.direct_referral_bonus_amount ?? null,
  // pairing_bonus_enabled: props.tenant?.pairing_bonus_enabled ?? false,
  // pairing_bonus_amount: props.tenant?.pairing_bonus_amount ?? null,
  // pairing_bonus_max_pairs: props.tenant?.pairing_bonus_max_pairs ?? null,
  // pairing_bonus_fifth_pairs_enabled: props.tenant?.pairing_bonus_fifth_pairs_enabled ?? false,
  // flush_out_enabled: props.tenant?.flush_out_enabled ?? false,
  // pairing_bonus_max_waiting_points: props.tenant?.pairing_bonus_max_waiting_points ?? null,
  // infinity_bonus_enabled: props.tenant?.infinity_bonus_enabled ?? false,
  // infinity_bonus_amount: props.tenant?.infinity_bonus_amount ?? null,
  // infinity_bonus_starting_level: props.tenant?.infinity_bonus_starting_level ?? null,
  // region_tagging_bonus_enabled: props.tenant?.region_tagging_bonus_enabled ?? false,
  // region_tagging_bonus_commission_mode: props.tenant?.region_tagging_bonus_commission_mode ?? null,
  // region_tagging_bonus_amount: props.tenant?.region_tagging_bonus_amount ?? null,
  // ticketing_enabled: props.tenant?.ticketing_enabled ?? false,
  // eloading_enabled: props.tenant?.eloading_enabled ?? false,
  // insurance_enabled: props.tenant?.insurance_enabled ?? false,
  // bills_payment_enabled: props.tenant?.bills_payment_enabled ?? false,
  // color_primary: props.tenant?.color_primary ?? null,
  // color_success: props.tenant?.color_success ?? null,
  // color_error: props.tenant?.color_error ?? null,
  // color_warning: props.tenant?.color_warning ?? null,
  // color_info: props.tenant?.color_info ?? null,
  // logo: props.tenant?.logo ?? null,
  name: 'MegaDC',
  domain: 'megadc',
  database: 'megadc',
  contact_person: 'John Jackson Betito',
  email: 'johnjacksonbetito8@example.com',
  mobile_number: '09451339125',
  province: props.provinces[0],
  municipality: props.provinces[0].municipalities[0],
  additional_address_information: 'House 32, Zone 71, Elias Angeles Street',
  status: 'active',
  market_plan: 'binary',
  max_sub_accounts: 7,
  account_upgrades_enabled: true,
  can_only_upgrade_once: true,
  account_levels: [{name: 'Silver', cost: 2000}, {name: 'Gold', cost: 4000}, {name: 'Diamond', cost: 6000}],
  direct_referral_bonus_enabled: true,
  direct_referral_bonus_amount: 200,
  pairing_bonus_enabled: true,
  pairing_bonus_amount: 500,
  pairing_bonus_max_pairs: 10,
  pairing_bonus_fifth_pairs_enabled: true,
  flush_out_enabled: true,
  pairing_bonus_max_waiting_points: 3000,
  infinity_bonus_enabled: true,
  infinity_bonus_amount: 100,
  infinity_bonus_starting_level: 2,
  region_tagging_bonus_enabled: true,
  region_tagging_bonus_commission_mode: 'fixed',
  region_tagging_bonus_amount: 100,
  ticketing_enabled: true,
  eloading_enabled: true,
  insurance_enabled: true,
  bills_payment_enabled: true,
  color_primary: '#6265ef',
  color_success: '#4aad36',
  color_error: '#c20a23',
  color_warning: '#ffa900',
  color_info: '#317cf5',
  logo: null,
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
    ...form.data(),
    province_id: form.province?.id ?? null,
    municipality_id: form.municipality?.id ?? null,
  })

  if (props.disabled) {
    return
  }

  if (props.tenant) {
    form.transform(transformFunction).post(`/management/tenants/${props.tenant.id}`, options)
    return
  }

  form.transform(transformFunction).post('/management/tenants', options)
}
</script>

<template>
  <v-sheet style="display: flex; flex-direction: column;">
    <v-form @submit.prevent="onSubmitHandler" :disabled="form.processing || disabled">
      <v-row class="mt-4 border-b">
        <v-col cols="12" class="pb-12">
          <TenantBasicInformationForm
              :form="form"
              :provinces="provinces"
              :disabled="disabled"
          />
        </v-col>
      </v-row>

      <v-row class="mt-4 border-b">
        <v-col cols="12" class="pb-12">
          <TenantConfigurationForm
              :form="form"
              :disabled="disabled"
          />
        </v-col>
      </v-row>

      <v-row class="mt-4 border-b">
        <v-col cols="12" class="pb-12">
          <TenantServiceSettings
              :form="form"
              :disabled="disabled"
          />
        </v-col>
      </v-row>

      <v-row class="mt-4 border-b">
        <v-col cols="12" class="pb-12">
          <TenantBrandingForm
              :form="form"
              :disabled="disabled"
          />
        </v-col>
      </v-row>
      <v-row class="mt-4">
        <v-col cols="12" class="pb-12 text-right">
          <v-btn
              v-if="disabled"
              type="submit"
              variant="flat"
              color="primary"
              min-width="120px"
              :loading="form.processing"
              @click="router.get(`/management/tenants/${tenant.id}/edit`)"
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
        </v-col>
      </v-row>
    </v-form>
  </v-sheet>

</template>
