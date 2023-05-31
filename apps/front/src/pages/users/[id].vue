<template>
  <h1>Update user</h1>
  <label for="email">Email</label>
  <input
    v-model="user.email"
    name="email"
    type="text"
  >

  <label for="password">Password</label>
  <input
    v-model="user.password"
    name="password"
    type="password"
  >

  <button @click="updateUser">
    Save
  </button>
</template>

<script setup lang="ts">
import {ref} from "vue";
import useGetUser from "~/composables/api/user/useGetUser";
import useUpdateUser from "~/composables/api/user/useUpdateUser";

const route = useRoute();

const password = ref('');

const { data: user, pending: userPending, refresh: userRefresh } = await useGetUser(route.params.id as string);

const updateUser = async () => {
  await useUpdateUser(route.params.id as string, user.value);
  userRefresh();
};
</script>


<style scoped lang="scss">

</style>