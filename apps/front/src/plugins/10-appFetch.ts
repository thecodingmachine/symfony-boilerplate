import { useAuthUser } from "~/store/auth";
import { API_URL } from "~/constants/http";

export default defineNuxtPlugin((nuxtApp) => {
  //const pinia = usePinia();
  const pinia = nuxtApp.vueApp.config.globalProperties.$pinia;
  const event = useRequestEvent();
  const headers: {
    [key: string]: string;
  } = useRequestHeaders(["cookie"]) as {
    [key: string]: string;
  };
  const store = useAuthUser(pinia);
  const appFetch = $fetch.create({
    baseURL: API_URL,
    headers: {
      Accept: "application/json",
      // Send to API custom headers + specific cookies
      ...headers,
    },
    //ignoreResponseError: true,
    onResponse(context) {
      const t = nuxtApp.vueApp.config.globalProperties.$t;
      const toast = nuxtApp.vueApp.config.globalProperties.$toast;
      const res = context.response;
      const cookies = res.headers.get("set-cookie") || "";
      if (process.server && cookies) {
        // Send to browser cookies
        event.node.res.setHeader("set-cookie", cookies);
      }
      if (res.status === 401 && store.isAuthenticated) {
        logger.error("401 error, removing authentication informations");
        toast.add({
          severity: "error",
          summary: t("plugins.appFetch.toasterUnauthorizedSummary"),
          detail: t("plugins.appFetch.toasterUnauthorizedDetail"),
          life: 3000,
        });
        store.resetAuth();
        return;
      }
      if (res?.status && res.status === 403) {
        logger.error("Unauthorized");
        toast.add({
          severity: "error",
          summary: t("plugins.appFetch.toasterForbiddenSummary"),
          detail: t("plugins.appFetch.toasterForbiddenDetail"),
          life: 0,
        });
        return;
      }
      if (!res?.status || res?.status >= 500) {
        toast.add({
          severity: "error",
          summary: t("plugins.appFetch.toasterCatchAllSummary"),
          detail: t("plugins.appFetch.toasterCatchAllDetail"),
          life: 3000,
        });
        return;
      }
    },
  });
  return {
    provide: {
      // // https://nuxt.com/docs/getting-started/data-fetching#example-pass-client-headers-to-the-api
      appFetch,
    },
  };
});
