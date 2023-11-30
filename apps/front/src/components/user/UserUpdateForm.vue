<template>
  <div
    v-show="pendingData"
    v-t="{ path: 'components.user.updateForm.pending' }"
  ></div>
  <h1 v-t="{ path: 'components.user.updateForm.title', args: data }"></h1>
  <UserForm
    :default-value="data"
    :violations="violations"
    @submit="submit"
    @cancel="navigateToList"
  >
  </UserForm>
  {{ error }}
</template>

<script setup lang="ts">
import useGetUser from "~/composables/api/user/useGetUser";
import useUpdateUser from "~/composables/api/user/useUpdateUser";
import type { UserInput } from "~/types/UserInput";

interface Props {
  userId: string;
}

const props = defineProps<Props>();

const { violations, updateUser: updateUserApi } = useUpdateUser();

const { data, error, pending: pendingData } = await useGetUser(props.userId);

const submit = async (value: UserInput) => {
  // If you need to copy the value, create a clone so it does not track reactivity
  //data.value = {...data.value, ...value};
  await updateUserApi(props.userId, value);
  return navigateTo("/users/");
};
const navigateToList = () => {
  return navigateTo("/users/");
};
</script>
<style scoped lang="scss"></style>
