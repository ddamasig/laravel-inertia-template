<script setup>
import {Head, useForm, usePage} from '@inertiajs/vue3';
import {mdiAccount, mdiEmail, mdiLock} from "@mdi/js";
import {computed, reactive} from "vue";

const page = usePage();

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const state = reactive({
  loading: false
})

const form = useForm({
  username: '',
  password: '',
  remember: false,
});

const onSubmitHandler = () => {
  state.loading = true
  form.transform(data => ({
    ...data,
    remember: form.remember ? 'on' : '',
  })).post(route('login'), {
    onFinish: () => {
      state.loading = false
      form.reset('password')
    }
  });
};

const customErrorMessage = computed(() => {
  let message = page.props?.errors?.custom
  return message ?? null;
})

</script>

<template>
  <Head title="Log in"/>

  <v-container style="height: 100vh" fluid class="bg-primary-faded">
    <v-row class="fill-height" align-content="center" justify="center">
      <v-col style="max-width: 400px">
        <v-img src="/images/logo.png"/>
        <v-form @submit.prevent="onSubmitHandler" :disabled="state.loading">
          <v-alert
              v-if="customErrorMessage"
              title="Failed to Login"
              :text="customErrorMessage"
              type="error"
              class="mb-2"
              variant="flat"
          />
          <v-card border variant="flat">
            <v-card-text class="pb-0">
              <v-text-field
                  v-model="form.username"
                  label="Username"
                  variant="outlined"
                  density="compact"
                  type="username"
                  color="primary"
                  :prepend-inner-icon="mdiAccount"
                  @input="form.clearErrors('username')"
                  :error="!!form.errors.username"
                  :error-messages="form.errors.username"
                  class="mb-4"
              />
              <v-text-field
                  v-model="form.password"
                  label="Password"
                  variant="outlined"
                  density="compact"
                  type="password"
                  color="primary"
                  class="mb-4"
                  :prepend-inner-icon="mdiLock"
                  @input="form.clearErrors('password')"
                  :error="!!form.errors.password"
                  :error-messages="form.errors.password"
              />
              <v-checkbox
                  v-model="form.remember"
                  color="primary"
                  label="Remember Me"
                  hide-details
                  density="compact"
              />
            </v-card-text>
            <v-card-actions class="px-4">
              <v-btn
                  color="primary"
                  variant="text"
                  style="min-width: 100px;"
                  :href="route('password.request')"
              >
                Forgot Password?
              </v-btn>
              <v-spacer/>
              <v-btn
                  color="primary"
                  variant="flat"
                  style="min-width: 100px;"
                  type="submit"
                  :loading="state.loading"
                  :disabled="!form.isDirty"
              >
                Login
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-form>
      </v-col>
    </v-row>

  </v-container>

</template>
