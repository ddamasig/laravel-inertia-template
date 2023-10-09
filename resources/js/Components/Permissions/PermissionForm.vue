<script setup>
import {router, useForm} from "@inertiajs/vue3";
import {reactive} from "vue";

const props = defineProps({
  permission: Object,
  disabled: Boolean
})

const form = useForm({
  name: props.permission?.name ?? null,
  description: props.permission?.description ?? null
})

const state = reactive({
  loading: false
})

const onSubmitHandler = () => {
  const options = {
    preserveState: true,
    onStart: () => state.loading = true,
    onFinish: () => state.loading = false,
    onError: (error) => console.error(error)
  }

  if (props.disabled) {
    return
  }

  if (props.permission) {
    form.put(`/permissions/${props.permission.id}`, options)
    return
  }

  form.post('/permissions', options)
}
</script>

<template>
  <v-form @submit.prevent="onSubmitHandler" :disabled="state.loading">
    <v-card elevation="0" border max-width="700px" :loading="state.loading">
      <template v-slot:loader>
        <v-progress-linear color="primary" indeterminate v-show="state.loading"/>
      </template>
      <v-card-title class="mb-1">
        Basic Information
      </v-card-title>
      <v-card-text>
        <v-text-field
            v-model="form.name"
            label="Name"
            variant="outlined"
            density="compact"
            color="primary"
            class="mb-4"
            autofocus
            @input="form.clearErrors('name')"
            :error="!!form.errors.name"
            :error-messages="form.errors.name"
            :readonly="disabled"
        />
        <v-textarea
            v-model="form.description"
            label="Description"
            variant="outlined"
            density="compact"
            color="primary"
            auto-grow
            @input="form.clearErrors('description')"
            :error="!!form.errors.description"
            :error-messages="form.errors.description"
            :readonly="disabled"
        />
      </v-card-text>
      <v-card-actions
          class="px-4"
      >
        <v-spacer/>
        <v-btn
            v-if="disabled"
            type="submit"
            variant="flat"
            color="primary"
            min-width="120px"
            :loading="state.loading"
            @click="router.get(`/permissions/${permission.id}/edit`)"
        >
          Edit
        </v-btn>
        <v-btn
            v-if="!disabled"
            type="submit"
            variant="flat"
            color="primary"
            min-width="120px"
            :loading="state.loading"
        >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>
