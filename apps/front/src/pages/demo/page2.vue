<template>
  <div>
    <div>Page 2</div>
    <div v-if="data">
      {{ data.success }}
    </div>
  </div>
</template>
<script lang="ts" setup>
import { useHealthCheckFetch } from '~/composables/api/healthCheck/useHealthCheckFetch';

const {
  data
} = await useHealthCheckFetch();
</script>
