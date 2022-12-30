import {
  AsyncDataOptions,
  FetchResult,
  UseFetchOptions,
  useNuxtApp,
} from '#app';
import { NitroFetchRequest } from 'nitropack';
import { KeyOfRes } from 'nuxt/dist/app/composables/asyncData';
import { FetchError } from 'ofetch';
import { hash } from 'ohash';
import { Ref } from 'vue';

// This might break the "pick" strategy from nuxt

type AppReturn<T> = Promise<{
  data: T;
  error: Error | unknown | null;
  pending: boolean;
  refresh: () => void;
}>;

// from https://github.com/nuxt/framework/blob/main/packages/nuxt/src/app/composables/fetch.ts
// So we disable our linting to keep similar code
// We add the ReturnType in the generic so we can type hint API return
// + we use our own wrapper appFetch
/* eslint-disable */
export default function useAppFetch<
  ReturnType = any,
  ResT = void,
  ErrorT = FetchError,
  ReqT extends NitroFetchRequest = NitroFetchRequest,
  _ResT = ResT extends void ? FetchResult<ReqT> : ResT,
  Transform extends (
    res: _ResT) => any = (res: _ResT) => _ResT,
  PickKeys extends KeyOfRes<Transform> = KeyOfRes<Transform>,
>(
  request: Ref<ReqT> | ReqT | (() => ReqT),
  arg1?: string | UseFetchOptions<_ResT, Transform, PickKeys>,
  arg2?: string,
): AppReturn<ReturnType> {
  const { $appFetch } = useNuxtApp();
  const [opts = {}, autoKey] = typeof arg1 === 'string' ? [{}, arg1] : [arg1, arg2];
  const _key = opts.key;
  hash([
    autoKey,
    unref(opts.baseURL),
    typeof request === 'string' ? request : '',
    unref(opts.params),
  ]);
  if (!_key || typeof _key !== 'string') {
    throw new TypeError(`[nuxt] [useFetch] key must be a string: ${_key}`);
  }
  if (!request) {
    throw new Error('[nuxt] [useFetch] request is missing.');
  }
  const key = _key === autoKey ? `$f${_key}` : _key;

  const _request = computed(() => {
    let r = request;
    if (typeof r === 'function') {
      r = r();
    }
    return unref(r);
  });

  const {
    server,
    lazy,
    default: defaultFn,
    transform,
    pick,
    watch,
    immediate,
    ...fetchOptions
  } = opts;

  const _fetchOptions = reactive({
    ...fetchOptions,
    cache: typeof opts.cache === 'boolean' ? undefined : opts.cache,
  });

  const _asyncDataOptions: AsyncDataOptions<_ResT, Transform, PickKeys> = {
    server,
    lazy,
    default: defaultFn,
    transform,
    pick,
    immediate,
    watch: [_fetchOptions, _request, ...(watch || [])],
  };

  let controller: AbortController;

  const asyncData = useAsyncData<_ResT, ErrorT, Transform, PickKeys>(
    key,
    () => {
      controller?.abort?.();
      controller = typeof AbortController !== 'undefined'
        ? new AbortController()
        : ({} as AbortController);
      return $appFetch(_request.value, {
        signal: controller.signal,
        ..._fetchOptions,
      } as any) as Promise<_ResT>;
    },
    _asyncDataOptions,
  );

  // This force the type
  return asyncData as unknown as AppReturn<ReturnType>;
}
