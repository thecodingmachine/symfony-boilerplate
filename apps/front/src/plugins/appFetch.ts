// TODO : remove that

import { NitroFetchOptions, NitroFetchRequest } from "nitropack";
import { useAuthUser } from "~/store/auth";
import { API_URL } from "~/constants/http";

export default defineNuxtPlugin(() => {
  const store = useAuthUser();
  const event = useRequestEvent();
  const headers: {
    [key: string]: string;
  } = {
    ...(useRequestHeaders(["cookie"]) as {
      [key: string]: string;
    }),
    ...{
      accept: "application/json",
    },
  };
  const handleException = (e: any) => {
    logger.error("An error hapenned during an API call");
    logger.error(e);
    // Check if 401 so remove auth info
    if (e && e.response && e.response.status === 401 && store.isAuthenticated) {
      logger.error("401 error, removing authentication informations");
      store.resetAuth();
    }
    const cookies = e.response.headers.get("set-cookie") || "";
    if (process.server && cookies) {
      event.node.res.setHeader("set-cookie", cookies);
    }
    throw e;
  };
  const fetchRaw = async <T>(
    request: NitroFetchRequest,
    opts?: NitroFetchOptions<"json">
  ) => {
    const res = await $fetch.raw<T>(request, {
      headers,
      baseURL: API_URL,
      ...opts,
    });

    const cookies = res.headers.get("set-cookie") || "";
    if (process.server && cookies) {
      event.node.res.setHeader("set-cookie", cookies);
    }
    return res;
  };

  const fetchNative = async <T>(
    request: NitroFetchRequest,
    opts?: NitroFetchOptions<"json">
  ) => {
    const res = await $fetch.raw<T>(request, {
      headers,
      baseURL: API_URL,
      ...opts,
    });

    const cookies = res.headers.get("set-cookie") || "";
    if (process.server && cookies) {
      event.node.res.setHeader("set-cookie", cookies);
    }
    return res._data;
  };

  const _appFetchRaw = async <T>(
    request: NitroFetchRequest,
    opts?: NitroFetchOptions<"json">
  ) => {
    try {
      return await fetchRaw<T>(request, opts);
    } catch (e: any) {
      handleException(e);
    }
  };
  const appFetch = async <T>(
    request: NitroFetchRequest,
    opts?: NitroFetchOptions<"json">
  ) => {
    try {
      return await fetchNative<T>(request, opts);
    } catch (e: any) {
      handleException(e);
    }
  };
  appFetch.raw = _appFetchRaw;
  // When you use appFetch.create, you should ensure that you dont need to bridge cookies
  appFetch.create = $fetch.create;

  return {
    provide: {
      // // https://nuxt.com/docs/getting-started/data-fetching#example-pass-client-headers-to-the-api
      appFetch,
    },
  };
});
