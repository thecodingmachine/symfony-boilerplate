<template>
  <h1>Register</h1>
  <div>
    <label for="email">Email</label>
    <input v-model="email" name="email" type="text">
  </div>

  <div>
    <label for="password">Password</label>
    <input v-model="password" name="password" type="password">
  </div>

  <div>
    <label for="password2">Confirm password</label>
    <input v-model="password2" name="password2" type="password">
    <span v-if="password2 && password !== password2" class="text-danger">
      The confirm password is not the same as the password
    </span>
  </div>

  <button
    :disabled="!formValidation"
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
const password2 = ref('');
const formValidation = computed(() =>
  email.value
  && password.value
  && password2.value
  && (password.value === password2.value)
);

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