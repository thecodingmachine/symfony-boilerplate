<template>
  <h1>Users</h1>
  <table>
    <thead>
      <tr>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in userStore.users">
        <td>{{ user.email }}</td>
        <td>
          <NuxtLink
            v-if="user.id !== authStore.authUser.id"
            :to="`/users/${user.id}`"
          >
            <button>Update</button>
          </NuxtLink>
        </td>
        <td>
          <button
            v-if="user.id !== authStore.authUser.id"
            @click="deleteUser(user)"
          >
            Delete
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup lang="ts">
import useUser from "~/store/user";
import {User} from "~/utils/types";
import useAuthUser from "~/store/auth";

const userStore = useUser();
const authStore = useAuthUser();

onMounted(async () => {
  await userStore.listUsers();
});

const deleteUser = async (user: User) => {
  await userStore.deleteUser(user);
};
</script>


<style scoped lang="scss">

</style>