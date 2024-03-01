<template>
  <div>
    <Toast />
    <NuxtLoadingIndicator
      color="linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(0,18,71,1) 60%, rgba(0,236,174,1) 100%)"
    />
    <NuxtLayout v-if="!isMePending || isAuthenticated">
      <NuxtPage />
    </NuxtLayout>
  </div>
</template>
<script lang="ts" setup>
import { watchEffect } from "vue";
import { useAuthUser } from "~/store/auth";
import { storeToRefs } from "pinia";

const authStore = useAuthUser();

const { isAuthenticated, isMePending, authUrl } = storeToRefs(authStore);

// Doing this here instead than in the middleware allow reactivity on the auth user
watchEffect(
  async () => {
    if (isMePending.value) {
      return;
    }
    const routeRequest = useRequestURL();
    const href = routeRequest.href;
    const shouldRedirectToLogin =
      !isAuthenticated.value &&
      authUrl.value &&
      decodeURIComponent(href) !== decodeURIComponent(authUrl.value);
    if (shouldRedirectToLogin) {
      return navigateTo(authUrl.value, { external: true });
    }
  },
  {
    flush: "pre",
  },
);
</script>
