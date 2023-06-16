<template>
  <div>
    <NuxtErrorBoundary @error="mHandleError">
      <NuxtLayout v-if="shouldRender">
        <NuxtPage />
      </NuxtLayout>
    </NuxtErrorBoundary>
  </div>
</template>
<script setup lang="ts">
import { watchEffect } from "vue";
import { useAuthUser } from "~/store/auth";

const authStore = useAuthUser();

const route = useRoute();

const mHandleError = (e: any) => {
  logger.error("Primary error boundary", e);
};
const shouldRender = computed(
  () => !authStore.isPending || authStore.isAuthenticated
);
// Doing this here instead than in the middleware allow reactivity on the auth user
watchEffect(async () => {
  logger.info("pending");
  if (authStore.isPending) {
    return;
  }
  logger.info("resolved pending");
  const shouldRedirectToLogin =
    !authStore.isAuthenticated &&
    authStore.authUrl &&
    route.name !== "auth-login";
  logger.info("shouldRedirectToLogin");
  logger.info(authStore.isAuthenticated);
  logger.info(authStore.authUrl);
  logger.info(route.name);
  logger.info(shouldRedirectToLogin);
  if (shouldRedirectToLogin) {
    await navigateTo(authStore.authUrl, { external: true });
  }
  const shouldRedirectToHomepage =
    authStore.isAuthenticated && route.name === "auth-login";
  if (shouldRedirectToHomepage) {
    await navigateTo("/");
  }
});
</script>
cd
