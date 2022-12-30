<template>
  <div class="row justify-content-end py-2 mb-2">
    <div class="col-2">
      <p v-if="email">
        Welcome {{ email }}
      </p>
    </div>
  </div>
</template>
<script setup lang="ts">
// import {} from "s"
import { useAuthUser } from '~/store/auth';

const authStore = useAuthUser();

const email = authStore.authUser?.email || '';
</script>
