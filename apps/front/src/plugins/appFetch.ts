// TODO : remove that

import { useAuthUser } from "~/store/auth";
import { API_URL } from "~/constants/http";

export default defineNuxtPlugin(() => {
  const pinia = usePinia();
  const store = useAuthUser(pinia);
  const event = useRequestEvent();
  const headers: {
    [key: string]: string;
  } = useRequestHeaders(["cookie"]) as {
    [key: string]: string;
  };
  const appFetch = $fetch.create({
    baseURL: API_URL,
    headers,
    onResponse(context) {
      const res = context.response;
      const cookies = res.headers.get("set-cookie") || "";
      if (res.status === 401 && store.isAuthenticated) {
        logger.error("401 error, removing authentication informations");
        store.resetAuth();
      }
      if (process.server && cookies) {
        event.node.res.setHeader("set-cookie", cookies);
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
