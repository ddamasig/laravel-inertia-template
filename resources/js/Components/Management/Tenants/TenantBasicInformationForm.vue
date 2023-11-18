<script setup>
import TextField from "@/Components/Common/Forms/TextField.vue";
import SearchableSelect from "@/Components/Common/Forms/SearchableSelect.vue";
import FormSection from "@/Components/Common/Forms/FormSection.vue";

const props = defineProps({
    loading: Boolean,
    provinces: Array,
    disabled: Boolean,
    form: Object,
})

/**
 * Clear the form.municipality if the province is updated.
 */
const onChangeProvince = () => {
    props.form.municipality = null
}

</script>

<template>
    <FormSection
        title="Basic Information"
        subtitle="The fields marked with asterisks(*) are required."
        :loading="loading"
    >
        <TextField
            v-model="form.name"
            label="Name *"
            name="name"
            :form="form"
            autofocus
        />
        <TextField
            v-model="form.database"
            label="Database Name *"
            name="database"
            :form="form"
        />
        <TextField
            v-model="form.domain"
            label="Domain *"
            name="domain"
            :form="form"
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
    </FormSection>

</template>
