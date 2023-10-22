<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import {mdiEmail, mdiLock} from "@mdi/js";
import {reactive} from "vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const state = reactive({
    loading: false
})

const form = useForm({
    email: '',
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
</script>

<template>
    <Head title="Log in"/>

    <v-container style="height: 100vh" fluid>
        <v-row class="fill-height" align-content="center" justify="center">
            <v-col style="max-width: 400px">
                <v-form @submit.prevent="onSubmitHandler" :disabled="state.loading">
                    <v-card border variant="flat">
                        <div class="bg-primary">
                            <v-list-item>
                                <template #prepend>
                                    <v-avatar image="/images/logo.png" size="72" class="pa-4"/>
                                </template>
                                <v-list-item-subtitle>
                                    Welcome to
                                </v-list-item-subtitle>
                                <v-list-item-title>
                                    <span style="font-size: 1.75rem; font-weight: bolder">Generic App</span>
                                </v-list-item-title>

                            </v-list-item>
                        </div>
                        <v-card-text class="pb-0">
                            <v-text-field
                                v-model="form.email"
                                label="E-mail Address"
                                variant="outlined"
                                density="compact"
                                type="email"
                                color="primary"
                                hide-details
                                :prepend-inner-icon="mdiEmail"
                                @input="form.clearErrors('email')"
                                :error="!!form.errors.email"
                                :error-messages="form.errors.email"
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
                                hide-details
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
