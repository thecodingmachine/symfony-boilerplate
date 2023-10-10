<template>
  <div class="grid">
    <div class="col-2 col-offset-8"></div>
    <div class="col-2">
      <p
        v-if="username"
        v-t="{
          path: 'components.layout.appHeader.welcome',
          args: { username },
        }"
      ></p>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useAuthUser } from "~/store/auth";

const authStore = useAuthUser();

const username = authStore.me.email || "";
</script>
