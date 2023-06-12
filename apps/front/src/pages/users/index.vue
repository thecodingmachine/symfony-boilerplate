<template>
  <h1>Users</h1>
  <table>
    <thead>
      <tr>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users">
        <td>{{ user.email }}</td>
        <td>
          <NuxtLink
            v-if="!authStore.isAuthUser(user)"
            :to="`/users/${user.id}`"
          >
            <button>Update</button>
          </NuxtLink>
        </td>
        <td>
          <button
            v-if="!authStore.isAuthUser(user)"
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
import {User} from "~/utils/types";
import useAuthUser from "~/store/auth";
import useListUsers from "~/composables/api/user/useListUsers";
import {Ref} from "vue";
import useDeleteUser from "~/composables/api/user/useDeleteUser";

const authStore = useAuthUser();
let users: Ref<User[]> = ref([]);

onMounted(async () => {
  users.value = await useListUsers();
});

const deleteUser = async (user: User) => {
  await useDeleteUser(user);
  users.value = users.value.filter(userItem => userItem.id !== user.id);
};
</script>


<style scoped lang="scss">

</style>