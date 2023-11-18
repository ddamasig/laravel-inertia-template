<script setup>
import {useAlertStore} from "@/Stores/AlertStore.js";
import Checkbox from "@/Components/Common/Forms/Checkbox.vue";
import FormSection from "@/Components/Common/Forms/FormSection.vue";

const alert = useAlertStore()
const props = defineProps({
  tenant: Object,
  form: Object,
  provinces: Array,
  disabled: Boolean,
  loading: Boolean
})

</script>

<template>
  <FormSection
      title="Available Services"
      subtitle="Please determine which services should be enabled for the Tenant."
      :loading="loading"
  >
    <Checkbox
        v-model="form.ticketing_enabled"
        label="Airline Ticketing *"
        name="ticketing_enabled"
        :form="form"
        hide-details
        type="ticketing_enabled"
    />
    <Checkbox
        v-model="form.eloading_enabled"
        label="E-Loading *"
        name="eloading_enabled"
        :form="form"
        hide-details
        type="eloading_enabled"
    />
    <Checkbox
        v-model="form.insurance_enabled"
        label="Insurance *"
        name="insurance_enabled"
        :form="form"
        hide-details
        type="insurance_enabled"
    />
    <Checkbox
        v-model="form.bills_payment_enabled"
        label="Bills Payment *"
        name="bills_payment_enabled"
        :form="form"
        hide-details
        type="bills_payment_enabled"
    />
  </FormSection>


</template>
