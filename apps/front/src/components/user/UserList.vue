<template>
  <div>
    <div
      v-show="usersPending"
      v-t="{ path: 'components.user.list.pending' }"
    ></div>
    <div v-show="error">{{ error }}}</div>
    {{ errorDelete }}
    <table v-if="users">
      <thead>
        <tr>
          <th>{{ $t("components.user.list.title") }}</th>
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
              <Button severity="secondary">{{
                $t("components.user.list.edit")
              }}</Button>
            </NuxtLink>
          </td>
          <td>
            <Button
              v-if="!authStore.isAuthUser(user)"
              severity="danger"
              @click="deleteUserClick(user)"
            >
              {{ $t("components.user.list.delete") }}
            </Button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import type { User } from "~/types/User";
import useAuthUser from "~/store/auth";
import useListUsers from "~/composables/api/user/useListUsers";
import useDeleteUser from "~/composables/api/user/useDeleteUser";

const authStore = useAuthUser();
const { deleteUser, errorMessage: errorDelete } = useDeleteUser();

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
    throw e;
  }
};
</script>

<style scoped lang="scss"></style>
