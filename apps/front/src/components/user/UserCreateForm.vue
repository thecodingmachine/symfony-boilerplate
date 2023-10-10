<template>
  <h1>Register</h1>
  <form @submit.prevent.stop="registerUser">
    <UserForm
      v-model:email="localReactive.email"
      v-model:password="password"
      v-model:password-confirm="passwordConfirm"
      :is-password-confirmed="isPasswordConfirmed"
    />
    {{ errorMessage }}
    <button :disabled="!securedPassword">Register</button>
  </form>
</template>
<script setup lang="ts">
import useCreateUser from "~/composables/api/user/useCreateUser";
import useUser from "~/composables/user/useUser";

const { createUser, errorMessage } = useCreateUser();
const {
  localReactive,
  password,
  passwordConfirm,
  isPasswordConfirmed,
  securedPassword,
} = useUser();

const registerUser = async () => {
  if (!securedPassword.value) {
    // That mean you call this action without checking that data are OK. this shall not be executed
    throw createError("You need a valid password");
  }
  try {
    await createUser(localReactive.email, securedPassword.value);
    await navigateTo("/users");
  } catch (e) {
    logger.info(e);
  }
};
</script>

<style scoped lang="scss"></style>
