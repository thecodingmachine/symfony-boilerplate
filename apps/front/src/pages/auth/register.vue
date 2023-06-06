<template>
  <h1>Register</h1>
  <label for="email">Email</label>
  <input v-model="email" name="email" type="text">

  <label for="password">Password</label>
  <input v-model="password" name="password" type="password">

  <button
    :disabled="!email"
    @click="registerUser"
  >
    Register
  </button>
</template>
<script setup lang="ts">
import {ref} from "vue";
import useAuthUser from "~/store/auth";

const authStore = useAuthUser();

const email = ref();
const password = ref();

const registerUser = async () => {
  await authStore.registerUser(email.value, password.value);
  navigateTo('/users');
};
</script>

<style scoped lang="scss">

</style>