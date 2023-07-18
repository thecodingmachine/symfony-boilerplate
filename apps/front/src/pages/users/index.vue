<template>
  <div>
    <h1>Users</h1>
    <div v-show="usersPending">(En cours de chargement)</div>
    <div v-show="error">{{ error }}}</div>
    <table v-if="!usersPending && users">
      <thead>
        <tr>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
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
              @click="deleteUserClick(user)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { User } from "~/types/user";
import useAuthUser from "~/store/auth";
import useListUsers from "~/composables/api/user/useListUsers";
import useDeleteUser from "~/composables/api/user/useDeleteUser";

const authStore = useAuthUser();
const { deleteUser } = useDeleteUser();

const {
  data: users,
  error,
  pending: usersPending,
  refresh: usersRefresh,
} = await useListUsers();

const deleteUserClick = async (user: User) => {
  try {
    await deleteUser(user);
    usersRefresh();
  } catch (e) {
    logger.error(e);
  }
};
</script>

<style scoped lang="scss"></style>
