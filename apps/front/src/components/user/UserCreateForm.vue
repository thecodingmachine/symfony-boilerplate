<template>
  <h1 v-t="{ path: 'components.user.createForm.title' }"></h1>
  <form @submit.prevent.stop="registerUser">
    <UserForm
      v-model:email="email"
      v-model:password="password"
      v-model:password-confirm="passwordConfirm"
      :is-password-confirmed="isPasswordConfirmed"
    />
    {{ errorMessage }}
    <button :disabled="!isPasswordConfirmed">
      {{ $t("components.user.createForm.ok") }}
    </button>
  </form>
</template>
<script setup lang="ts">
import useCreateUser from "~/composables/api/user/useCreateUser";
import useUser from "~/composables/user/useUser";

const { createUser, errorMessage } = useCreateUser();
const {
  email,
  password,
  passwordConfirm,
  isPasswordConfirmed,
  securedPassword,
} = useUser();

const registerUser = async () => {
  try {
    await createUser(email.value, securedPassword.value);
    await navigateTo("/users");
  } catch (e) {
    logger.info(e);
  }
};
</script>

<style scoped lang="scss"></style>
