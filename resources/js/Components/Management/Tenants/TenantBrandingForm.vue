<script setup>
import {reactive, watch} from "vue";
import TextField from "@/Components/Common/Forms/TextField.vue";
import ImageUploader from "@/Components/Common/Forms/ImageUploader.vue";
import FormSection from "@/Components/Common/Forms/FormSection.vue";

const props = defineProps({
  form: Object,
  isOnEditMode: Boolean,
  disabled: Boolean,
  loading: Boolean
})

const colors = ['color_primary', 'color_success', 'color_error', 'color_info', 'color_warning']

const state = reactive({
  focused_color: null
})

const setFocusedColor = (color) => {
  state.focused_color = color
  props.form.active_color = props.form[state.focused_color]
}

watch(props.form, () => {
  if (!state.focused_color) {
    return
  }

  props.form[state.focused_color] = props.form.active_color
})
</script>

<template>

  <FormSection
      title="Branding Customization"
      subtitle="Manage the look and feel of your company website."
      :loading="loading"
  >
    <ImageUploader
        v-model="props.form.logo"
        label="Logo"
        name="logo"
        :form="props.form"
        :default="{
                  data: props.tenant?.logo,
                  preview: props.tenant?.logo?.preview_url
                }"
        :disabled="disabled"
    />

    <h3>Brand Colors</h3>
    <v-list-item
        class="pt-2"
        v-for="color in colors"
        :key="color"
    >
      <template #prepend>
        <v-menu
            :close-on-content-click="false"
            location="start"
        >
          <template v-slot:activator="{ props }">
            <v-avatar
                v-bind="props"
                :color="form[color] ?? '#F0F0F0' "
                class="mt-3 cursor-pointer"
                rounded
                @click="setFocusedColor(color)"
            />
          </template>
          <v-sheet class="pa-4" elevation="4">
            <h3>Color Picker</h3>
            <v-color-picker
                v-show="!!state.focused_color"
                v-model="form.active_color"
                mode="hex"
                elevation="0"
                class="mt-2"
                :disabled="!state.focused_color"
            />
          </v-sheet>
        </v-menu>
      </template>
      <template #title>
        <TextField
            v-model="form[color]"
            :label="`${color.replace('_', ' ').toUpperCase()} *`"
            name="color_primary"
            class="mt-3"
            :form="form"
            read-only
            hide-details
            @focus="state.focused_color = color"
        />
      </template>
    </v-list-item>
  </FormSection>

</template>
