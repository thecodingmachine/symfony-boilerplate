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
import {Ref, ref} from "vue";
import useGetUser from "~/composables/api/user/useGetUser";
import useUpdateUser from "~/composables/api/user/useUpdateUser";
import {User} from "~/utils/types";

const route = useRoute();

const password = ref('');
const user: Ref<User|undefined> = ref();
user.value = await useGetUser(route.params.id as string);

const updateUser = async () => {
  user.value = await useUpdateUser(route.params.id as string, user.value!);
};
</script>


<style scoped lang="scss">

</style>