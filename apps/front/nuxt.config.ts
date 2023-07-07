// https://nuxt.com/docs/api/configuration/nuxt-config
import svgLoader from "vite-svg-loader";

export default defineNuxtConfig({
  modules: [
    '@pinia/nuxt',
    '@nuxtjs/i18n',
  ],
  runtimeConfig: {
    API_URL: process.env.API_URL || '',
  },
  app: {
    head: {
      // @see https://getbootstrap.com/docs/5.0/getting-started/introduction/#starter-template
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      title: 'Boilerplate',
      meta: [
        // <meta name="description" content="My amazing site">
        //  { name: 'description', content: 'My amazing site.' }
      ],
    },
  },
  css: [
    'primevue/resources/themes/bootstrap4-light-blue/theme.css',
    'primevue/resources/primevue.css',
    'primeflex/primeflex.scss',
    '@/assets/styles/main.scss',
  ],
  build: {
    transpile: ['primevue'],
  },
  vite: {
    plugins: [
      svgLoader(),
    ],
    css: {
      preprocessorOptions: {
        scss: {
          additionalData: '@import "@/assets/styles/tools/_colors.scss";@import "@/assets/styles/tools/_mixins.scss";',
        },
      },
    },
  },
  i18n: {
    locales: [
      {
        code: 'en',
        iso: 'en-US',
        name: 'English',
        file: 'en.ts',
      },
      {
        code: 'fr',
        iso: 'fr-FR',
        name: 'Français',
        file: 'fr.ts',
      },
    ],
    defaultLocale: 'en',
    strategy: 'no_prefix',
    lazy: true,
    langDir: 'lang',
    detectBrowserLanguage: {
      useCookie: true,
      cookieKey: 'i18n_redirected',
    },
    vueI18n: './i18n.config.ts',
  }
});
