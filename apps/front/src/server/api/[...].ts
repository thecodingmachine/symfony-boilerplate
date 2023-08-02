import {
  defineEventHandler,
  H3Event,
  RequestHeaders,
  getMethod,
  getRequestHeaders,
  sendProxy as _sendProxy,
} from "h3";
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

import logger from "~/utils/logger";

/**
 * This code override proxyRequest from h3
 */

/**
 * start - this code is not exported from h3
 */
export interface ProxyOptions {
  headers?: RequestHeaders | HeadersInit;
  fetchOptions?: RequestInit;
  fetch?: typeof fetch;
  sendStream?: boolean;
  cookieDomainRewrite?: string | Record<string, string>;
  cookiePathRewrite?: string | Record<string, string>;
  onResponse?: (event: H3Event, response: Response) => void;
}

const ignoredHeaders = new Set([
  'transfer-encoding',
  'connection',
  'keep-alive',
  'upgrade',
  'expect',
  'host',
]);

export function getProxyRequestHeaders(event: H3Event) {
  const headers = Object.create(null);
  const reqHeaders = getRequestHeaders(event);
  // eslint-disable-next-line no-restricted-syntax
  for (const name in reqHeaders) {
    if (!ignoredHeaders.has(name)) {
      headers[name] = reqHeaders[name];
    }
  }
  return headers;
}

/**
 * end - this code is not exported from h3
 */

/**
 * This code override h3 default behavior:
 * Instead of reading the body we send the body as the stream, allowing better memory usage + prevent utf8 decoding
 * */
async function proxyRequest(
  event: H3Event,
  target: string,
  opts: ProxyOptions = {},
) {
  const method = getMethod(event);

  // Headers
  const headers = getProxyRequestHeaders(event);
  if (opts.fetchOptions?.headers) {
    Object.assign(headers, opts.fetchOptions.headers);
  }
  if (opts.headers) {
    Object.assign(headers, opts.headers);
  }
  return _sendProxy(event, target, {
    ...opts,
    fetchOptions: {
      headers,
      method,
      body: method !== "GET" && method !== "HEAD" ? event.node.req : undefined,
      ...opts.fetchOptions,
    } as RequestInit,
  });
}

/**
 * Here we use the proxy as a default request
 */
export default defineEventHandler(async (event: H3Event) => {
  const { API_URL } = useRuntimeConfig();
  const target = new URL(event.req.url as string, API_URL);
  logger.info("----API Proxy");
  const ret = await proxyRequest(event, target.toString(), {
    headers: {
      host: target.host,
    },
    sendStream: true,
    fetchOptions: {
      redirect: "manual",
    },
  });
  return ret;
});
