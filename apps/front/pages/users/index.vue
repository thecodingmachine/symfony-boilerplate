<template>
  <h1>{{ $t('users.users') }}</h1>
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
            <button>{{ $t('global.update')}}</button>
          </NuxtLink>
        </td>
        <td>
          <button
            v-if="!authStore.isAuthUser(user)"
            @click="deleteUser(user)"
          >
            {{ $t('global.delete') }}
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup lang="ts">
import {User} from "~/types/user";
import useAuthUser from "~/store/auth";
import useListUsers from "~/composables/api/user/useListUsers";
import useDeleteUser from "~/composables/api/user/useDeleteUser";

const authStore = useAuthUser();

const { data: users, pending: usersPending, refresh: usersRefresh } = await useListUsers();

const deleteUser = async (user: User) => {
  await useDeleteUser(user);
  usersRefresh();
};
</script>


<style scoped lang="scss">

</style>