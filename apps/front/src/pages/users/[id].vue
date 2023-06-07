<template>
  <h1>Update user: {{ userStore.user ? userStore.user.email : '' }}</h1>
  <label for="email">Email</label>
  <input v-model="email" name="email" type="text">

  <label for="password">Password</label>
  <input v-model="password" name="password" type="password">

  <button @click="updateUser">
    Save
  </button>
</template>

<script setup lang="ts">
import useUser from "~/store/user";
import {ref} from "vue";

const userStore = useUser();
const route = useRoute();

let email = ref();
const password = ref();

onMounted(async () => {
  await userStore.getUser(route.params.id as string);
  email.value = userStore.user!.email;
});

const updateUser = async () => {
  await userStore.updateUser(route.params.id as string, { email: email.value, password: password.value });
};
</script>


<style scoped lang="scss">

</style>