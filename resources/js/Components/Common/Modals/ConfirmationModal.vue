<script setup>
import {useConfirmModalStore} from "@/Stores/ConfirmModalStore.js";


const modal = useConfirmModalStore()

</script>

<template>
    <v-dialog v-model="modal.isOpen" max-width="600px">
        <v-card>
            <v-card-title>{{ modal.title }}</v-card-title>
            <v-card-text>
                {{ modal.content }}
            </v-card-text>
            <v-card-actions>
                <v-spacer/>
                <v-btn
                    color="muted"
                    variant="text"
                    @click="modal.cancel"
                    :disabled="modal.isLoading"
                >
                    Cancel
                </v-btn>
                <v-btn
                    color="primary"
                    variant="flat"
                    @click="modal.confirm"
                    :loading="modal.isLoading"
                >
                    Confirm
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
