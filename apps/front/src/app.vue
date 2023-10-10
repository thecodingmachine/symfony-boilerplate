<template>
  <div>
    <NuxtErrorBoundary @error="mHandleError">
      <NuxtLoadingIndicator color="linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(0,18,71,1) 60%, rgba(0,236,174,1) 100%)"/>
      <NuxtLayout v-if="!isMePending || isAuthenticated">
        <NuxtPage />
      </NuxtLayout>
    </NuxtErrorBoundary>
  </div>
</template>
<script setup lang="ts">
import { watchEffect } from "vue";
import { useAuthUser } from "~/store/auth";
import { storeToRefs } from "pinia";

const authStore = useAuthUser();

const route = useRoute();

const mHandleError = (e: unknown) => {
  logger.error("Primary error boundary", e);
};
const { isAuthenticated, isMePending, authUrl } = storeToRefs(authStore);
// Doing this here instead than in the middleware allow reactivity on the auth user
watchEffect(async () => {
  if (isMePending.value) {
    return;
  }
  const shouldRedirectToLogin =
    !isAuthenticated.value && authUrl.value && route.fullPath !== authUrl.value;
  if (shouldRedirectToLogin) {
    return navigateTo(authUrl.value, { external: true });
  }
  const shouldRedirectToHomepage =
    isAuthenticated.value && route.fullPath === authUrl.value;
  if (shouldRedirectToHomepage) {
    return navigateTo("/");
  }
});
</script>
