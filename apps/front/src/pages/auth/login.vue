<template>
  <div>
    <div v-if="!authStore.isAuthenticated">
      <h1>Welcome to the login page</h1>
      <label for="email">
        email
      </label>
      <input v-model="email" name="email" type="text">
      <button type="submit" @click="submitAuthenticateUser">
        Log-in
      </button>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import { useAuthUser } from '~/store/auth';

definePageMeta({
  layout: 'anonymous',
});

const authStore = useAuthUser();
/**
 *
 *  If we’re using ref or reactive to store our state, they don’t get saved and passed along.
 * So when the client is booted up, they don’t have any value and we need to rerun our setup
 * code on the client.
 *
 *   Normally, this is fine.
 * It only becomes an issue when your ref relies on state from the server,
 * such as a header from the request, or data fetched during the server-rendering process.
 * * */
const email = ref('user@kb.com');
const submitAuthenticateUser = async () => {
  await authStore.authenticateUser(email.value, '');
};
</script>
