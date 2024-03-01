<template>
  <main id="main" class="main">
    <section class="login flex-nowrap">
      <div class="welcome-box">
        <div class="content-box no-padding">
          <h1>{{ $t("pages.auth.login.title") }}</h1>
        </div>
      </div>
      <form
        class="login-form temporary-primary-bg"
        @submit.prevent.stop="submitAuthenticateUser"
      >
        <InputText
          v-model="username"
          :placeholder="$t('pages.auth.login.username')"
          type="text"
        />
        <InputText
          v-model="password"
          :placeholder="$t('pages.auth.login.password')"
          type="password"
        />

        <Button type="submit"> {{ $t("pages.auth.login.ok") }}</Button>
        <div v-if="errorMessage">{{ errorMessage }}</div>
      </form>
    </section>
  </main>
</template>
<script lang="ts" setup>
import { ref } from "vue";
import { useAuthUser } from "~/store/auth";
import type { AppFetch } from "~/types/AppFetch";
import useBasicError from "~/composables/useBasicError";
import useInternalUrl from "~/composables/url/useInternalUrl";

definePageMeta({
  layout: "anonymous",
  middleware: ["redirect-authenticated"],
});

const authStore = useAuthUser();
const route = useRoute();
const { errorMessage, setError, resetError } = useBasicError();
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
const username = ref("");
const password = ref("");
const { $appFetch }: { $appFetch: AppFetch<any> } = useNuxtApp();
const internalUrl = useInternalUrl();
const submitAuthenticateUser = async () => {
  resetError();
  try {
    const continueUrl = "" + route.query.returnTo;
    await authStore.authenticateUser(username.value, password.value, $appFetch);
    if (route.query.returnTo && internalUrl.isInternalUrl(continueUrl)) {
      return await navigateTo(continueUrl, { external: true });
    }
    return await navigateTo("/");
  } catch (e: any) {
    await setError(e);
  }
};
</script>
