<template>
  <div class="row justify-content-end py-2 mb-2">
    <div class="col-2">
      <p v-if="username">Welcome {{ username }}</p>
    </div>
  </div>
</template>
<script setup lang="ts">
// import {} from "s"
import { useAuthUser } from "~/store/auth";

const authStore = useAuthUser();

const username = authStore.authUser?.username || "";
</script>
