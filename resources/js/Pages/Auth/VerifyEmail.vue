<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {mdiEmail} from "@mdi/js";
import TextField from "@/Components/Common/Forms/TextField.vue";

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head title="Email Verification" />

    <v-container style="height: 100vh" fluid class="bg-primary-faded">
        <v-row class="fill-height" align-content="center" justify="center">
            <v-col style="max-width: 400px">
                <v-form @submit.prevent="submit" :disabled="form.processing">
                    <v-card border variant="flat">
                        <v-card-title>
                            Resend Verification Email
                        </v-card-title>
                        <v-card-text class="pb-0">
                            <p class="mb-4">
                                Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                            </p>

                            <p v-if="verificationLinkSent" class="text-success">
                                A new verification link has been sent to the email address you provided in your profile settings.
                            </p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer/>
                            <v-btn
                                :href="route('logout')"
                                method="post"
                                variant="text"
                            >
                                Log Out
                            </v-btn>
                            <v-btn color="primary" type="submit" variant="flat" :loading="form.processing">
                                Resend Verification Email
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-col>
        </v-row>
    </v-container>
</template>
