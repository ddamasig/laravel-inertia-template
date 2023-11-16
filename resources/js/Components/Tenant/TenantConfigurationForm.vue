<script setup>
import TextField from "@/Components/Common/Forms/TextField.vue";
import Select from "@/Components/Common/Forms/Select.vue";
import SwitchField from "@/Components/Common/Forms/SwitchField.vue";
import Checkbox from "@/Components/Common/Forms/Checkbox.vue";
import {mdiCheck, mdiPlus, mdiTrashCan} from "@mdi/js";
import FeatureStatusIndicator from "@/Components/Tenant/FeatureStatusIndicator.vue";
import FormSection from "@/Components/Common/Forms/FormSection.vue";

const props = defineProps({
    isOnEditMode: Boolean,
    form: Object,
    disabled: Boolean,
    loading: Boolean
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
const regionTaggingBonusCommissionModes = [
    {
        name: 'Fixed Value',
        value: 'fixed'
    },
    {
        name: 'Percentage',
        value: 'percentage'
    },
]

const createAnotherAccountLevel = () => {
    props.form.account_levels.push({
        name: '',
        cost: 0
    })
}

const removeAccountLevel = (index) => {
    props.form.account_levels.splice(index, 1)
}

</script>

<template>
    <FormSection
        title="Feature Configuration"
        subtitle="Please determine the Market Plan and enabled features for this Tenant."
        :loading="loading"
    >
        <Select
            v-model="form.market_plan"
            label="Market Plan *"
            name="market_plan"
            :form="form"
            :items="marketPlanOptions"
            item-title="name"
        />
        <TextField
            v-model="form.max_sub_accounts"
            label="Maximum Number of Sub Accounts *"
            name="max_sub_accounts"
            :form="form"
            type="number"
        />
        <template v-if="form.market_plan === 'binary'">
            <h3 class="my-4">Binary Market Plan Configuration</h3>

            <v-expansion-panels :model-value="0" multiple>
                <v-expansion-panel elevation="0" class="border mb-1">
                    <v-expansion-panel-title class="font-weight-bold">
                        Account Upgrades
                        <FeatureStatusIndicator v-if="!!form.account_upgrades_enabled"/>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <SwitchField
                            v-model="form.account_upgrades_enabled"
                            :label="form.account_upgrades_enabled ? 'Enabled' : 'Disabled'"
                            name="account_upgrades_enabled"
                            :form="form"
                            autofocus
                            type="account_upgrades_enabled"
                            :disabled="disabled || isOnEditMode"
                        />
                        <template v-if="form.account_upgrades_enabled">
                            <Checkbox
                                v-model="form.can_only_upgrade_once"
                                label="Can Only Upgrade Once *"
                                name="can_only_upgrade_once"
                                :form="form"
                                autofocus
                                type="can_only_upgrade_once"
                                :disabled="disabled || isOnEditMode"
                            />

                            <div
                                v-for="(level, index) in form.account_levels"
                                :key="index"
                                style="width: 100% !important; display: flex; flex-direction: row;"
                                class="mb-3"
                            >
                                <TextField
                                    v-model="form.account_levels[index].name"
                                    :label="`Level ${index + 1}`"
                                    :name="`account_levels.${index}.name`"
                                    :form="form"
                                    autofocus
                                    class="mr-2"
                                    hide-details
                                    :disabled="disabled || isOnEditMode"
                                />
                                <TextField
                                    v-model="form.account_levels[index].cost"
                                    label="Cost to Upgrade *"
                                    :name="`account_levels.${index}.cost`"
                                    prefix="PHP"
                                    :form="form"
                                    type="number"
                                    hide-details
                                    :disabled="disabled "
                                />

                                <v-btn
                                    v-if="!isOnEditMode"
                                    variant="text"
                                    color="error"
                                    class="mt-1"
                                    @click="removeAccountLevel(index)"
                                >
                                    <v-icon :icon="mdiTrashCan"></v-icon>
                                </v-btn>

                            </div>

                            <v-btn
                                v-if="!isOnEditMode"
                                :prepend-icon="mdiPlus"
                                variant="text"
                                color="primary"
                                block
                                @click="createAnotherAccountLevel"
                            >
                                Add Another Level
                            </v-btn>
                        </template>
                    </v-expansion-panel-text>
                </v-expansion-panel>
                <!-- Direct Referral Bonus -->
                <v-expansion-panel elevation="0" class="border mb-1">
                    <v-expansion-panel-title class="font-weight-bold">
                        Direct Referral Bonus
                        <FeatureStatusIndicator v-if="!!form.direct_referral_bonus_enabled"/>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <SwitchField
                            v-model="form.direct_referral_bonus_enabled"
                            :label="form.direct_referral_bonus_enabled ? 'Enabled' : 'Disabled'"
                            name="direct_referral_bonus_enabled"
                            :form="form"
                            autofocus
                            type="direct_referral_bonus_enabled"
                            :disabled="disabled"
                        />
                        <template v-if="form.direct_referral_bonus_enabled">
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

                <!-- Pairing Bonus -->
                <v-expansion-panel elevation="0" class="border mb-1">
                    <v-expansion-panel-title class="font-weight-bold">
                        Pairing Bonus
                        <FeatureStatusIndicator v-if="!!form.pairing_bonus_enabled"/>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <SwitchField
                            v-model="form.pairing_bonus_enabled"
                            :label="form.pairing_bonus_enabled ? 'Enabled' : 'Disabled'"
                            name="pairing_bonus_enabled"
                            :form="form"
                            autofocus
                        />
                        <template v-if="form.pairing_bonus_enabled">
                            <TextField
                                v-model="form.pairing_bonus_amount"
                                label="Pairing Bonus Amount *"
                                name="pairing_bonus_amount"
                                prefix="PHP"
                                :form="form"
                                type="number"
                            />
                            <TextField
                                v-model="form.pairing_bonus_max_pairs"
                                label="Max Number of Pairs *"
                                name="pairing_bonus_max_pairs"
                                :form="form"
                                type="number"
                            />
                            <SwitchField
                                v-model="form.pairing_bonus_fifth_pairs_enabled"
                                :label=" form.pairing_bonus_fifth_pairs_enabled ? 'Fifth Pairs Enabled' : 'Fifth Pairs Disabled'"
                                name="pairing_bonus_fifth_pairs_enabled"
                                :form="form"
                                type="pairing_bonus_fifth_pairs_enabled"
                                hide-details
                            />
                            <SwitchField
                                v-model="form.flush_out_enabled"
                                :label=" form.flush_out_enabled ? 'Flush Out Enabled' : 'Flush Out Disabled'"
                                name="flush_out_enabled"
                                :form="form"
                                type="flush_out_enabled"
                            />
                            <TextField
                                v-model="form.pairing_bonus_max_waiting_points"
                                label="Max Waiting Points *"
                                name="pairing_bonus_max_waiting_points"
                                :form="form"
                                type="number"
                            />
                        </template>
                    </v-expansion-panel-text>
                </v-expansion-panel>

                <v-expansion-panel elevation="0" class="border mb-1">
                    <v-expansion-panel-title class="font-weight-bold">
                        Infinity Bonus
                        <FeatureStatusIndicator v-if="!!form.infinity_bonus_enabled"/>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <!-- Infinity Bonus -->
                        <SwitchField
                            v-model="form.infinity_bonus_enabled"
                            :label="form.infinity_bonus_enabled ? 'Enabled' : 'Disabled'"
                            name="infinity_bonus_enabled"
                            :form="form"
                            type="infinity_bonus_enabled"
                        />
                        <template v-if="form.infinity_bonus_enabled">
                            <TextField
                                v-model="form.infinity_bonus_amount"
                                label="Infinity Bonus Amount *"
                                name="infinity_bonus_amount"
                                prefix="PHP"
                                :form="form"
                                type="number"
                            />
                            <TextField
                                v-model="form.infinity_bonus_starting_level"
                                label="Infinity Starting Level *"
                                name="infinity_bonus_starting_level"
                                :form="form"
                                type="number"
                            />
                        </template>
                    </v-expansion-panel-text>
                </v-expansion-panel>

                <v-expansion-panel elevation="0" class="border mb-1">
                    <v-expansion-panel-title class="font-weight-bold">
                        Region Tagging Bonus
                        <FeatureStatusIndicator v-if="!!form.region_tagging_bonus_enabled"/>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <!-- Region Tagging -->
                        <SwitchField
                            v-model="form.region_tagging_bonus_enabled"
                            :label="form.region_tagging_bonus_enabled ? 'Enabled' : 'Disabled'"
                            name="region_tagging_bonus_enabled"
                            :form="form"
                            type="region_tagging_bonus_enabled"
                        />
                        <template v-if="form.region_tagging_bonus_enabled">
                            <Select
                                v-model="form.region_tagging_bonus_commission_mode"
                                label="Commission Mode *"
                                name="region_tagging_bonus_commission_mode"
                                :form="form"
                                :items="regionTaggingBonusCommissionModes"
                                item-title="name"
                            />
                            <TextField
                                v-if="form.region_tagging_bonus_commission_mode === 'fixed'"
                                v-model="form.region_tagging_value"
                                label="Commission - Fixed Value *"
                                name="region_tagging_bonus_amount"
                                :form="form"
                                type="number"
                            />
                            <TextField
                                v-if="form.region_tagging_bonus_commission_mode === 'percentage'"
                                v-model="form.region_tagging_value"
                                label="Commission - Percentage *"
                                name="region_tagging_bonus_amount"
                                suffix="%"
                                :form="form"
                                type="number"
                            />
                        </template>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </template>
    </FormSection>

</template>
