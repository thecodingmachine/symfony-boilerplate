<template>
  <h1>Register</h1>
  <label for="email">Email</label>
  <input v-model="email" name="email" type="text">

  <label for="password">Password</label>
  <input v-model="password" name="password" type="password">

  <button
    :disabled="!email || !password"
    @click="registerUser"
  >
    Register
  </button>
</template>
<script setup lang="ts">
import {ref} from "vue";
import useCreateUser from "~/composables/api/user/useCreateUser";

const email = ref('');
const password = ref('');

const registerUser = async () => {
  try {
    await useCreateUser(email.value, password.value);
    navigateTo('/users');
  } catch (e: any) {
    window.alert(e.data);
  }
};
</script>

<style scoped lang="scss">

</style>