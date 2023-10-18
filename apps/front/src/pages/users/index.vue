<template>
  <table v-if="users" class="custom-table">
    <thead>
      <tr>
        <th>{{ $t('pages.user.tableHeaders.email') }}</th>
        <th>{{ $t('pages.user.tableHeaders.firstName') }}</th>
        <th>{{ $t('pages.user.tableHeaders.lastName') }}</th>
        <th>{{ $t('pages.user.tableHeaders.score') }}</th>
        <th>{{ $t('pages.user.tableHeaders.pendingPayments') }}</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users" :key="user.id">
        <td>{{ user.email }}</td>
        <td>{{ user.first_name }}</td>
        <td>{{ user.last_name }}</td>
        <td>{{ user.score }}</td>
        <td>{{ user.payments_pending }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script setup lang="ts">
import useListUsers from "~/composables/api/user/useListUsers";

const {
  data: users,
  error,
  pending: usersPending,
  refresh: usersRefresh,
} = await useListUsers();

</script>

<style scoped lang="scss"></style>
