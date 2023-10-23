<template>
  <h1 v-t="{ path: 'components.user.createForm.title' }"></h1>
  <form @submit.prevent.stop="registerUser">
    <UserForm
      v-model:email="email"
      v-model:password="password"
      v-model:password-confirm="passwordConfirm"
      :is-password-confirmed="isPasswordConfirmed"
      v-model:profile-picture-file="profilePictureFile"
      :profile-picture-url="profilePictureUrl"
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
  profilePictureUrl
} = useUser();

const profilePictureFile = ref(File|null);


const registerUser = async () => {
  try {
    console.log(profilePictureFile.value);
    await createUser(email.value, securedPassword.value, profilePictureFile.value);
    await navigateTo("/users");
  } catch (e) {
    logger.info(e);
  }
};
</script>

<style scoped lang="scss"></style>
