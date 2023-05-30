import type { UseFetchOptions } from "nuxt/app";
import { defu } from "defu";
import { $Fetch, NitroFetchRequest } from "nitropack";

export default function useAppFetch<T>(
  url: string | Request | Ref<string | Request> | (() => string | Request),
  options: UseFetchOptions<T> = {}
) {
  const { $appFetch } = useNuxtApp();
  const defaults: UseFetchOptions<T> = {
    $fetch: $appFetch as $Fetch<unknown, NitroFetchRequest>,
  };

  // for nice deep defaults, please use unjs/defu
  const params = defu(options, defaults);

  return useFetch(url, params);
}
