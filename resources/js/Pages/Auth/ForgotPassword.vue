<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {mdiEmail, mdiLock} from "@mdi/js";

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'), {
        onFinish: () => {
            form.reset()
        }
    });
};
</script>

<template>
    <Head title="Forgot Password" />
    <v-container style="height: 100vh" fluid class="bg-primary-faded">
        <v-row class="fill-height" align-content="center" justify="center">
            <v-col style="max-width: 400px">
                <v-form @submit.prevent="submit" :disabled="form.processing">
                    <v-card border variant="flat">
                        <v-card-title>
                            Forgot your password?

                            <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ status }}
                            </div>
                        </v-card-title>
                        <v-card-text class="pb-0">
                            <p class="mb-4">
                                No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                            </p>
                            <v-text-field
                                v-model="form.email"
                                label="E-mail Address"
                                variant="outlined"
                                density="compact"
                                type="email"
                                color="primary"
                                :prepend-inner-icon="mdiEmail"
                                :error="!!form.errors.email"
                                :error-messages="form.errors.email"
                                class="mb-4"
                            />
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer/>
                            <v-btn color="primary" type="submit" variant="flat" :loading="form.processing">
                                Submit
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-col>
        </v-row>

    </v-container>
</template>
