import type { UseFetchOptions } from "nuxt/app";
import { defu } from "defu";
import type { $Fetch, NitroFetchRequest } from "nitropack";
import type { AsyncData } from "#app";
import { FetchError } from "ofetch";

export default function useAppFetch<T>(
  url: string | Request | Ref<string | Request> | (() => string | Request),
  options: UseFetchOptions<T | undefined | unknown> = {},
) {
  const { $appFetch } = useNuxtApp();
  const defaults: UseFetchOptions<T | undefined | unknown> = {
    $fetch: $appFetch as $Fetch<unknown, NitroFetchRequest>,
  };
  // for nice deep defaults, please use unjs/defu
  const params = defu(options, defaults);

  return useFetch<T | undefined | unknown>(url, params) as AsyncData<
    T,
    FetchError
  >;
}
