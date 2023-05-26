<template>
  <div>
    <NuxtErrorBoundary @error="mHandleError">
      <NuxtLayout>
        <NuxtPage />
      </NuxtLayout>
    </NuxtErrorBoundary>
  </div>
</template>
<script setup lang="ts">
import { watchEffect } from 'vue';
import { useAuthUser } from '~/store/auth';

const authStore = useAuthUser();

const route = useRoute();

const mHandleError = (e: unknown) => {
  logger.error('Primary error boundary', e);
};
const isPendingValue = computed(() => authStore.isPending);
// Doing this here instead than in the middleware allow reactivity on the auth user
watchEffect(async () => {
  if (isPendingValue.value) {
    return;
  }
  const shouldRedirectToLogin = !authStore.isAuthenticated && authStore.authUrl && route.name !== 'auth-login';
  if (shouldRedirectToLogin) {
    await navigateTo(authStore.authUrl, { external: true });
  }
  const shouldRedirectToHomepage = authStore.isAuthenticated && route.name === 'auth-login';
  if (shouldRedirectToHomepage) {
    await navigateTo('/');
  }
});
</script>
