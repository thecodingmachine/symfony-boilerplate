// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  srcDir: 'src/',
  modules: [
    '@pinia/nuxt',
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
    '@/assets/styles/main.scss',
  ],
  vite: {
    css: {
      preprocessorOptions: {
        scss: {
          additionalData: '@import "@/assets/styles/_functions.scss";@import "@/assets/styles/_variables.scss";@import "@/assets/styles/_mixins.scss";',
        },
      },
    },
  },
  // ssr: false

});
