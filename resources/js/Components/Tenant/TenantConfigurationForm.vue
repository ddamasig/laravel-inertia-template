<script setup>
import {useForm} from "@inertiajs/vue3";
import {computed, reactive} from "vue";
import {useAlertStore} from "@/Stores/AlertStore.js";
import TextField from "@/Components/Common/Forms/TextField.vue";
import Select from "@/Components/Common/Forms/Select.vue";
import SwitchField from "@/Components/Common/Forms/SwitchField.vue";
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
            Configuration
          </h3>
        </v-list-item-title>
        <v-list-item-subtitle>
          Please determine the Market Plan for this Tenant along with the features to be enabled.
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
            <Select
                v-model="form.market_plan"
                label="Market Plan *"
                name="market_plan"
                :form="form"
                :items="marketPlanOptions"
                item-title="name"
            />
            <TextField
                v-model="form.maximum_sub_accounts"
                label="Maximum Sub Accounts *"
                name="maximum_sub_accounts"
                :form="form"
                autofocus
                type="number"
            />

            <v-expansion-panels :model-value="0">
              <v-expansion-panel elevation="0" class="border mb-1">
                <v-expansion-panel-title>Account Upgrades</v-expansion-panel-title>
                <v-expansion-panel-text>
                  <SwitchField
                      v-model="form.has_account_upgrades"
                      label="Has Account Upgrades *"
                      name="has_account_upgrades"
                      :form="form"
                      autofocus
                      type="has_account_upgrades"
                  />
                  <template v-if="form.has_account_upgrades">
                    <Checkbox
                        v-model="form.can_only_upgrade_once"
                        label="Can Only Upgrade Once *"
                        name="can_only_upgrade_once"
                        :form="form"
                        autofocus
                        type="can_only_upgrade_once"
                    />
                    <div style="width: 100% !important; display: flex; flex-direction: row;">
                      <TextField
                          label="Level 1"
                          name="name"
                          :form="form"
                          autofocus
                          class="mr-2"
                      />
                      <TextField
                          label="Cost to Upgrade *"
                          name="cost"
                          prefix="PHP"
                          :form="form"
                          autofocus
                          type="number"
                      />
                    </div>
                    <div style="width: 100% !important; display: flex; flex-direction: row;">
                      <TextField
                          label="Level 2"
                          name="name"
                          :form="form"
                          autofocus
                          class="mr-2"
                      />
                      <TextField
                          label="Cost to Upgrade *"
                          name="cost"
                          prefix="PHP"
                          :form="form"
                          autofocus
                          type="number"
                      />
                    </div>
                    <div style="width: 100% !important; display: flex; flex-direction: row;">
                      <TextField
                          label="Level 3"
                          name="name"
                          :form="form"
                          autofocus
                          class="mr-2"
                      />
                      <TextField
                          label="Cost to Upgrade *"
                          name="cost"
                          prefix="PHP"
                          :form="form"
                          autofocus
                          type="number"
                      />
                    </div>
                    <div style="width: 100% !important; display: flex; flex-direction: row;">
                      <TextField
                          label="Level 4"
                          name="name"
                          :form="form"
                          autofocus
                          class="mr-2"
                      />
                      <TextField
                          label="Cost to Upgrade *"
                          name="cost"
                          prefix="PHP"
                          :form="form"
                          autofocus
                          type="number"
                      />
                    </div>
                  </template>
                </v-expansion-panel-text>
              </v-expansion-panel>
            </v-expansion-panels>

            <template v-if="form.market_plan === 'binary'">
              <h3 class="my-4">Earning Configuration</h3>
              <v-expansion-panels :model-value="0" multiple="">
                <!-- Direct Referral Bonus -->
                <v-expansion-panel elevation="0" class="border mb-1">
                  <v-expansion-panel-title>Direct Referral Bonus</v-expansion-panel-title>
                  <v-expansion-panel-text>
                    <SwitchField
                        v-model="form.has_direct_referral_bonus"
                        label="Has Direct Referral Bonus *"
                        name="has_direct_referral_bonus"
                        :form="form"
                        autofocus
                        type="has_direct_referral_bonus"
                    />
                    <template v-if="form.has_direct_referral_bonus">
                      <TextField
                          v-model="form.direct_referral_bonus_amount"
                          label="Direct Referral Bonus Amount *"
                          name="direct_referral_bonus_amount"
                          prefix="PHP"
                          :form="form"
                          autofocus
                          type="number"
                      />
                    </template>
                  </v-expansion-panel-text>
                </v-expansion-panel>

                <v-expansion-panel elevation="0" class="border mb-1">
                  <v-expansion-panel-title>Pairing Bonus</v-expansion-panel-title>
                  <v-expansion-panel-text>

                    <!-- Pairing Bonus -->
                    <SwitchField
                        v-model="form.has_pairing_bonus"
                        label="Has Pairing Bonus *"
                        name="has_pairing_bonus"
                        :form="form"
                        autofocus
                        type="has_pairing_bonus"
                    />
                    <template v-if="form.has_pairing_bonus">
                      <TextField
                          v-model="form.pairing_bonus_amount"
                          label="Pairing Bonus Amount *"
                          name="pairing_bonus_amount"
                          prefix="PHP"
                          :form="form"
                          autofocus
                          type="number"
                      />
                      <TextField
                          v-model="form.maximum_pairs"
                          label="Maximum Number of Pairs *"
                          name="maximum_pairs"
                          :form="form"
                          autofocus
                          type="number"
                      />
                      <Checkbox
                          v-model="form.has_fifth_pairs"
                          label="Has Fifth Pairs *"
                          name="has_fifth_pairs"
                          :form="form"
                          autofocus
                          type="has_fifth_pairs"
                      />

                      <Checkbox
                          v-model="form.has_flush_out"
                          label="Has Flush Out *"
                          name="has_flush_out"
                          :form="form"
                          autofocus
                          type="has_flush_out"
                      />

                      <TextField
                          v-model="form.maximum_waiting_points"
                          label="Maximum Waiting Points *"
                          name="maximum_waiting_points"
                          :form="form"
                          autofocus
                          type="number"
                      />
                    </template>
                  </v-expansion-panel-text>
                </v-expansion-panel>

                <v-expansion-panel elevation="0" class="border mb-1">
                  <v-expansion-panel-title>Infinity Bonus</v-expansion-panel-title>
                  <v-expansion-panel-text>
                    <!-- Infinity Bonus -->
                    <SwitchField
                        v-model="form.has_infinity_bonus"
                        label="Has Infinity Bonus *"
                        name="has_infinity_bonus"
                        :form="form"
                        autofocus
                        type="has_infinity_bonus"
                    />
                    <template v-if="form.has_infinity_bonus">
                      <TextField
                          v-model="form.infinity_bonus_amount"
                          label="Infinity Bonus Amount *"
                          name="infinity_bonus_amount"
                          prefix="PHP"
                          :form="form"
                          autofocus
                          type="number"
                      />
                      <TextField
                          v-model="form.infinity_bonus_starting_level"
                          label="Infinity Starting Level *"
                          name="infinity_bonus_starting_level"
                          :form="form"
                          autofocus
                          type="number"
                      />
                    </template>
                  </v-expansion-panel-text>
                </v-expansion-panel>

                <v-expansion-panel elevation="0" class="border mb-1">
                  <v-expansion-panel-title>Region Tagging</v-expansion-panel-title>
                  <v-expansion-panel-text>
                    <!-- Region Tagging -->
                    <SwitchField
                        v-model="form.has_region_tagging"
                        label="Has Region Tagging *"
                        name="has_region_tagging"
                        :form="form"
                        autofocus
                        type="has_region_tagging"
                    />
                    <template v-if="form.has_region_tagging">
                      <TextField
                          v-model="form.region_tagging_percentage"
                          label="Earning Percentage *"
                          name="region_tagging_percentage"
                          suffix="%"
                          :form="form"
                          autofocus
                          type="number"
                      />
                    </template>
                  </v-expansion-panel-text>
                </v-expansion-panel>
              </v-expansion-panels>
            </template>
          </v-card-text>
        </v-card>
      </v-form>
    </v-col>
  </v-row>

</template>
