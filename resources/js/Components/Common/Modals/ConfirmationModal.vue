<script setup>
const model = true

const props = defineProps({
    title: String,
    content: String,
    loading: Boolean,
});

defineEmits(['confirm', 'cancel'])

</script>

<template>
    <v-dialog v-model="model" max-width="600px">
        <v-card>
            <v-card-title>{{props.title}}</v-card-title>
            <v-card-text>
                {{props.content}}
            </v-card-text>
            <v-card-actions>
                <v-spacer/>
                <v-btn
                    color="muted"
                    variant="text"
                    @click="$emit('cancel')"
                    :disabled="loading"
                >
                    Cancel
                </v-btn>
                <v-btn
                    color="primary"
                    variant="flat"
                    @click="$emit('confirm')"
                    :loading="loading"
                >
                    Confirm
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
