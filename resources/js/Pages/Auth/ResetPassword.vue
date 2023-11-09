<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {mdiEmail} from "@mdi/js";
import TextField from "@/Components/Common/Forms/TextField.vue";

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Reset Password"/>
    <v-container style="height: 100vh" fluid class="bg-primary-faded">
        <v-row class="fill-height" align-content="center" justify="center">
            <v-col style="max-width: 400px">
                <v-form @submit.prevent="submit" :disabled="form.processing">
                    <v-card border variant="flat">
                        <v-card-title>
                            Reset your password
                        </v-card-title>
                        <v-card-text class="pb-0">
                            <p class="mb-4">
                                Please nominate a new password.
                            </p>
                            <TextField
                                v-model="form.email"
                                label="E-mail Address"
                                type="email"
                                :form="form"
                                name="email"
                                :prepend-inner-icon="mdiEmail"
                            />
                            <TextField
                                v-model="form.password"
                                label="Password"
                                type="password"
                                :form="form"
                                name="password"
                            />
                            <TextField
                                v-model="form.password_confirmation"
                                label="Confirm Password"
                                type="password"
                                :form="form"
                                name="password_confirmation"
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
