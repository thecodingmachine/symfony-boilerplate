import { useAuthUser } from "~/store/auth";
import { API_URL } from "~/constants/http";
import type { ComposerTranslation } from "vue-i18n";
import type { ToastServiceMethods } from "primevue/toastservice";

export default defineNuxtPlugin((nuxtApp) => {
  //const pinia = usePinia();
  const pinia = nuxtApp.vueApp.config.globalProperties.$pinia;
  const event = useRequestEvent();
  const headers: {
    [key: string]: string;
  } = useRequestHeaders(["cookie"]) as {
    [key: string]: string;
  };

  const appFetch = $fetch.create({
    baseURL: API_URL,
    retryStatusCodes: [408, 409, 425, 429, 502, 503, 504],
    headers: {
      Accept: "application/json",
      // Send to API custom headers + specific cookies
      ...headers,
    },
    onRequest({ options }) {
      const requestUrl = useRequestURL();
      const referer = requestUrl.href;

      if (!options.headers) {
        options.headers = {};
      }
      /** This is usefull to have the referer even during SSR, so on a 401 the backend can calculate a returnTO
       *
       * An interesting feature would be to send a refererPath (instead of the full href)
       * so the php would calculate a returnTo with the path,
       * allowing nuxt to consider the returnTo as non-external
       */
      options.headers = { ...options.headers, referer };
    },
    //ignoreResponseError: true,
    onResponse(context) {
      const store = useAuthUser(pinia);
      const t = nuxtApp.vueApp.config.globalProperties
        .$t as ComposerTranslation;
      const toast = nuxtApp.vueApp.config.globalProperties
        .$toast as ToastServiceMethods;
      const res = context.response;
      const cookies = res.headers.get("set-cookie") || "";
      if (process.server && cookies && event) {
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
