export default {
  /*
   ** Nuxt target
   ** See https://nuxtjs.org/api/configuration-target
   */
  target: 'server',
  /*
   ** SSR Feature (server side rendering)
   */
  ssr: process.env.NUXT_SSR === '1',
  /*
   ** Headers of the page
   ** See https://nuxtjs.org/api/configuration-head
   */
  head: {
    title: process.env.APP_NAME,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      {
        hid: 'description',
        name: 'description',
        content: process.env.npm_package_description || '',
      },
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
  },
  /*
   ** Global CSS
   */
  css: ['@/assets/css/main.scss'],
  /*
   ** Plugins to load before mounting the App
   ** https://nuxtjs.org/guide/plugins
   */
  plugins: ['@/plugins/i18n', '@/plugins/scroll-to-top.client'],
  /*
   ** Auto import components
   ** See https://nuxtjs.org/api/configuration-components
   */
  components: true,
  /*
   ** Nuxt.js dev-modules
   */
  buildModules: [
    // Doc: https://github.com/nuxt-community/eslint-module
    '@nuxtjs/eslint-module',
    // Doc: https://github.com/nuxt-community/stylelint-module
    '@nuxtjs/stylelint-module',
  ],

  publicRuntimeConfig: {
    appName: process.env.APP_NAME,
    apiURL: process.env.API_URL,
    GRAPHQL_ENDPOINT: process.env.GRAPHQL_URL,
  },

  privateRuntimeConfig: {
    // Secrets, only available on the server.
  },

  /*
   ** ROUTER
   */
  router: {
    // middleware: [],
  },

  loading: {
    color: '#a211fa',
    height: '3px',
  },

  /*
   ** Nuxt.js modules
   */
  modules: [
    'bootstrap-vue/nuxt',
    'nuxt-graphql-request',
    'nuxt-i18n',
    'cookie-universal-nuxt',
    'nuxt-logrocket',
    '@nuxtjs/toast',
  ],
  bootstrapVue: {
    icons: true,
    css: false,
    bvCSS: false,
    componentPlugins: [
      'LayoutPlugin',
      'FormPlugin',
      'LinkPlugin',
      'PaginationPlugin',
      'CardPlugin',
      'FormInputPlugin',
      'OverlayPlugin',
      'NavPlugin',
      'NavbarPlugin',
      'ButtonPlugin',
      'FormSelectPlugin',
      'FormGroupPlugin',
      'ImagePlugin',
      'SpinnerPlugin',
      'TablePlugin',
      'ButtonPlugin',
      'BreadcrumbPlugin',
      'FormFilePlugin',
      'BadgePlugin',
      'IconsPlugin',
      'VBTogglePlugin',
      'SidebarPlugin',
      'ModalPlugin',
    ],
    directivePlugins: [],
  },
  // See https://github.com/Gomah/nuxt-graphql-request.
  graphql: {
    /**
     * Your GraphQL endpoint (required)
     */
    // endpoint: process.env.GRAPHQL_URL,

    /**
     * Options
     * See: https://github.com/prisma-labs/graphql-request#passing-more-options-to-fetch
     */
    options: {
      credentials: 'include',
      mode: 'cors',
    },

    /**
     * Optional
     * default: true (this includes cross-fetch/polyfill before creating the graphql client)
     */
    useFetchPolyfill: true,

    /**
     * Optional
     * default: false (this includes graphql-tag for node_modules folder)
     */
    includeNodeModules: true,
  },
  i18n: {
    locales: [
      { code: 'en', file: 'en.js' },
      { code: 'fr', file: 'fr.js' },
    ],
    defaultLocale: process.env.DEFAULT_LOCALE,
    strategy: 'prefix_and_default',
    lazy: true,
    langDir: 'locales/',
    detectBrowserLanguage: {
      fallbackLocale: process.env.DEFAULT_LOCALE,
      onlyForRoot: true,
      useCookie: true,
      alwaysRedirect: true,
    },
    vuex: {
      moduleName: 'i18n',
      syncLocale: true,
    },
  },
  logRocket: {
    logRocketId: process.env.LOGROCKET_ID || '',
    devModeAllowed: process.env.LOGROCKET_DEV_MODE_ALLOWED || false,
  },
  toast: {
    position: 'bottom-center',
    duration: 5000,
  },
  /*
   ** Build configuration
   ** See https://nuxtjs.org/api/configuration-build/
   */
  build: {
    babel: {
      presets({ isServer }) {
        return [
          [
            require.resolve('@nuxt/babel-preset-app'),
            // require.resolve('@nuxt/babel-preset-app-edge'), // For nuxt-edge users
            {
              buildTarget: isServer ? 'server' : 'client',
              corejs: { version: 3 },
            },
          ],
        ]
      },
    },
  },
}
