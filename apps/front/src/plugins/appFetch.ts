// TODO : remove that

import { NitroFetchRequest } from 'nitropack';
import { FetchOptions } from 'ofetch';
import { useAuthUser } from '~/store/auth';

export default defineNuxtPlugin(() => {
  const store = useAuthUser();
  const event = useRequestEvent();
  const headers: {
    [key: string]: string;
  } = useRequestHeaders(['cookie']) as {
    [key: string]: string;
  };
  return {
    provide: {
      // // https://nuxt.com/docs/getting-started/data-fetching#example-pass-client-headers-to-the-api
      appFetch: async <T>(request: NitroFetchRequest, opts?: FetchOptions) => {
        try {
          const res = await $fetch.raw<T>(request, {
            headers,
            ...opts,
          });

          const cookies = (res.headers.get('set-cookie') || '').split(',');
          if (process.server && cookies) {
            event.res.setHeader('set-cookie', cookies);
          }
          return res._data; // eslint-disable-line
        } catch (e: any) {
          // Check if 401 so remove auth info
          if (
            e
            && e.response
            && e.response.status === 401
            && store.isAuthenticated
          ) {
            logger.error('401 error, removing authentication informations');
            store.resetAuth();
          }

          const cookies = (e.response.headers.get('set-cookie') || '').split(',');
          if (process.server && cookies) {
            event.res.setHeader('set-cookie', cookies);
          }
          throw e;
        }
      },
    },
  };
});
