import {
  defineEventHandler,
  H3Event,
  proxyRequest,
} from 'h3';

/**
 * Beware
 * Using the SSR, cookies are transmitted to the proxy BUT
 * the browser may not know which cookie to send, so you can end with
 * cookie: "PHPSESSIONID= deleted; PHPSESSIONID= SUPERID"
 *
 *   During the SSR, nuxt has no way to know which cookie to send to SF,
 * because he can not route the cookie based on the cookiePath
 *
 *   So the proxy may send the request only with cookie: "PHPSESSIONID= deleted;",
 * loosing the session in the process during the SSR
 *
 *   This behavior may impact cookie and any specific headers
 * (nuxt SSR and the browser does not have access to
 * the same info depending on the api route called, the browser having more context)
 *
 *   It is not possible to be sure to have an isometric behavior during SSR and browser rendering
 *
 *     A good way to prevent this would be to handle cookie in nuxt (session for example)
 * and retrive the cookie from nuxt. Then retrive and store API cookie in/from the nuxt cookie,
 * then use this retrived cookie in the proxy
 */

import logger from '~/utils/logger';

export default defineEventHandler(async (event: H3Event) => {
  const { API_URL } = useRuntimeConfig();
  const target = new URL(event.req.url as string, API_URL);
  logger.info('----API Proxy');
  const ret = await proxyRequest(event, target.toString(), {
    headers: {
      host: target.host,
    },

  });
  return ret;
});
