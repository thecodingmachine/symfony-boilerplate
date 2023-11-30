<template>
  <h1 v-t="{ path: 'components.user.createForm.title' }"></h1>
  <UserForm
    class="card"
    :violations="violations"
    @submit="submit"
    @cancel="navigateToList"
  >
    <template #buttons="{ isValid, cancel }">
      <Button type="button" severity="danger" class="mr-2 mb-2" @click="cancel">
        {{ $t("components.user.createForm.buttonCancel") }}
      </Button>
      <Button type="submit" :disabled="!isValid" class="mr-2 mb-2">
        {{ $t("components.user.createForm.ok") }}
      </Button>
    </template>
  </UserForm>
</template>
<script setup lang="ts">
import useCreateUser from "~/composables/api/user/useCreateUser";
import type { UserInput } from "~/types/UserInput";
const { createUser, violations } = useCreateUser();

const submit = async (state: UserInput) => {
  try {
    await createUser(state);
    await navigateTo("/users");
  } catch (e) {
    logger.info(e);
  }
};
const navigateToList = () => {
  return navigateTo("/users/");
};
</script>

<style scoped lang="scss"></style>
