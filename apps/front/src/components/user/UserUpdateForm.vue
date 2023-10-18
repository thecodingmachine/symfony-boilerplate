<template>
  <div
    v-show="userPending"
    v-t="{ path: 'components.user.updateForm.pending' }"
  ></div>
  <form v-if="data" @submit.prevent.stop="updateUser">
    <h1 v-t="{ path: 'components.user.updateForm.title', args: data }"></h1>
    <UserForm
      v-model:email="email"
      v-model:password="password"
      v-model:password-confirm="passwordConfirm"
      :is-password-confirmed="isPasswordConfirmed"
    />
    <Button type="submit" :disabled="!isPasswordConfirmed">{{
      $t("components.user.updateForm.ok")
    }}</Button>
  </form>
  <div>
    {{ errorMessage }}
    {{ error }}
  </div>
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
  await updateUserApi(
    data.value.id,
    {
      email: email.value,
    },
    securedPassword.value
  );
  userRefresh();
  await navigateTo("/users");
};
</script>

<style scoped lang="scss"></style>
