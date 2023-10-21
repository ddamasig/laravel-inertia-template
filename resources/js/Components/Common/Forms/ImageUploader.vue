<script setup>
import {computed, reactive, ref} from "vue";
import {mdiAccount} from "@mdi/js";

const props = defineProps({
  modelValue: String | Number,
  form: Object,
  name: String,
  label: String
})

const emit = defineEmits(['update:modelValue'])

const imageData = ref(null)
const imagePreview = ref(null)
const uploaderRef = ref(null)

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

const onTriggerUploader = () => {
  uploaderRef.value.click()
}

const onSelectFile = (event) => {
  const input = event.target;
  console.log('Files:')
  console.log(input.files)
  if (input.files) {
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    }
    imageData.value = input.files[0];
    reader.readAsDataURL(input.files[0]);
  }
  console.log('Image Data:')
  console.log(imageData.value)
}
</script>

<template>
  <v-list-item class="py-7 px-0">
    <template #prepend>
      <v-avatar
          color="primary"
          size="64"
          rounded
          :image="imagePreview"
      >
        <!--        <template #default>-->
        <!--          <v-icon size="32">{{mdiAccount}}</v-icon>-->
        <!--        </template>-->
      </v-avatar>
    </template>
    <v-list-item-title>
      Avatar | No image uploaded yet
    </v-list-item-title>
    <v-list-item-title>
      <v-btn
          @click.prevent="onTriggerUploader"
          variant="flat"
          color="primary"
          density="compact"
      >
        Upload
      </v-btn>
      <v-file-input
          v-show="false"
          v-model="value"
          @input="onInput"
          @change="onSelectFile"
          ref="uploaderRef"
          :label="props.label"
          variant="outlined"
          density="compact"
          color="primary"
          hide-details
          prepend-icon=""
          single-line
          accept="image/*"
          :error="!!errorMessage"
          :error-messages="errorMessage"
      />
    </v-list-item-title>
  </v-list-item>
</template>
