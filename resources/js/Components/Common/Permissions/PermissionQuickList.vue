<script setup>
import {computed} from "vue";

const props = defineProps({
  permissions: Array
})

const message = computed(() => {
  const isEmpty = props.permissions.length === 0;
  if (isEmpty) {
    return 'No assigned permissions'
  }
  return props.permissions.map(p => p.name).join(', ')
})

</script>

<template>
  <div class="px-2 mb-2">
    <span class="d-block font-weight-bold">
      Permissions:
    </span>
    {{ message}}
  </div>
</template>
