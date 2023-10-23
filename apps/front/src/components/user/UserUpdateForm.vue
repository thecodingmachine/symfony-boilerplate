<template>
  <div
    v-show="pendingData"
    v-t="{ path: 'components.user.updateForm.pending' }"
  ></div>
  <h1 v-t="{ path: 'components.user.updateForm.title', args: data }"></h1>
  <UserForm
    :default-value="data"
    @submit="submit"
    @cancel="navigateToList"
    @delete-user-picture="deleteUserPicture"
  >
  </UserForm>
  <div>
    {{ errorMessage }}
    {{ error }}
  </div>
</template>

<script setup lang="ts">
import useGetUser from "~/composables/api/user/useGetUser";
import useUpdateUser from "~/composables/api/user/useUpdateUser";
import useDeleteUserPicture from "~/composables/api/user/useDeleteUserPicture";
import type { UserInput } from "~/types/UserInput";
import { useToast } from "primevue/usetoast";

interface Props {
  userId: string;
}

const props = defineProps<Props>();

const { errorMessage, updateUser: updateUserApi } = useUpdateUser();
const {
  errorMessage: deleteUserPictureErrorMessage,
  deleteUserPicture: deleteUserPictureApi,
} = useDeleteUserPicture();

const {
  data,
  error,
  pending: pendingData,
} = await useGetUser(props.userId as string);

const toast = useToast();

const submit = async (value: UserInput) => {
  // If you need to copy the value, create a clone so it does not track reactivity
  //data.value = {...data.value, ...value};
  await updateUserApi(parseInt(props.userId), value);
  return navigateTo("/users/");
};

const navigateToList = () => {
  return navigateTo("/users/");
};

const deleteUserPicture = async () => {
  await deleteUserPictureApi(props.userId);
  toast.add({
    severity: "info",
    summary: "Confirmed",
    detail: "Record deleted",
    life: 3000,
  });
};
</script>
<style scoped lang="scss"></style>
