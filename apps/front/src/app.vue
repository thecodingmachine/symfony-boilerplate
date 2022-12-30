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

const mHandleError = (e) => {
  logger.error('Primary error boundary', e);
};

const { DISABLE_AUTHENTICATION } = useRuntimeConfig();
// Doing this here instead than in the middleware allow reactivity on the auth user
watchEffect(async () => {
  if (authStore.isPending) {
    return;
  }
  const shouldRedirectToLogin = !DISABLE_AUTHENTICATION && !authStore.isAuthenticated && authStore.authUrl && route.name !== 'auth-login';
  if (shouldRedirectToLogin) {
    await navigateTo(authStore.authUrl, { external: true });
  }
  const shouldRedirectToHomepage = authStore.isAuthenticated && route.name === 'auth-login';
  if (shouldRedirectToHomepage) {
    await navigateTo('/');
  }
});
</script>
