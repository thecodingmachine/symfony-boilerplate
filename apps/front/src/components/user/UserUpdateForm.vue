<template>
  <div v-show="userPending">(Chargement)</div>

  <form v-if="!userPending" @submit.prevent.stop="updateUser">
    <h1>Update user {{ data.email }}</h1>
    <UserForm
      v-model:email="email"
      v-model:password="password"
      v-model:password-confirm="passwordConfirm"
      :is-password-confirmed="isPasswordConfirmed"
    />
    {{ errorMessage }}
    {{ error }}
    <button type="submit">Save</button>
  </form>
</template>

<script setup lang="ts">
import useGetUser from "~/composables/api/user/useGetUser";
import useUpdateUser from "~/composables/api/user/useUpdateUser";
import useUser from "~/composables/user/useUser";

const props = defineProps<{
  userId: string;
}>();

const { errorMessage, updateUser: updateUserApi } = useUpdateUser();

const {
  data,
  error,
  pending: userPending,
  refresh: userRefresh,
} = await useGetUser(props.userId as string);

const {
  email,
  password,
  passwordConfirm,
  isPasswordConfirmed,
  securedPassword,
} = useUser(data);
const updateUser = async () => {
  try {
    await updateUserApi(
      data.value.id,
      {
        email: email.value,
      },
      securedPassword.value
    );
    userRefresh();
    await navigateTo("/users");
  } catch (e) {
    logger.error(e);
  }
};
</script>

<style scoped lang="scss"></style>
