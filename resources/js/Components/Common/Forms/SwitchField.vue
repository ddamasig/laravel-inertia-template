<script setup>
import {computed} from "vue";

const props = defineProps({
  modelValue: String | Number,
  form: Object,
  name: String,
  label: String
})
const emit = defineEmits(['update:modelValue'])

const value = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emit('update:modelValue', value)
  }
})

const errorMessage = computed({
  get() {
    const errors = props.form.errors[props.name]
    if (errors) {
      return errors
    }
    return null
  },
})

const onInput = () => {
  if (!!props.form) {
    props.form.clearErrors(props.name)
  }
}
</script>

<template>
  <v-switch
      v-model="value"
      @input="onInput"
      :label="props.label"
      variant="outlined"
      density="compact"
      color="primary"
      :error="!!errorMessage"
      :error-messages="errorMessage"
  />
</template>
