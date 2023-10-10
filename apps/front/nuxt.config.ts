// https://nuxt.com/docs/api/configuration/nuxt-config
import svgLoader from "vite-svg-loader";

export default defineNuxtConfig({
  srcDir: "src/",
  modules: ["@pinia/nuxt", "@nuxtjs/i18n"],
  runtimeConfig: {
    API_URL: process.env.API_URL || "",
  },
  i18n: {
    vueI18n: "./modules_config/nuxt/i18n.config.ts", // if you are using custom path, default
  },
  app: {
    head: {
      // @see https://getbootstrap.com/docs/5.0/getting-started/introduction/#starter-template
      charset: "utf-8",
      viewport: "width=device-width, initial-scale=1",
      title: "Boilerplate TCM v2",
      meta: [
        // <meta name="description" content="My amazing site">
        //  { name: 'description', content: 'My amazing site.' }
      ],
    },
  },
  css: [
    "@/assets/styles/main.scss",
    "primevue/resources/themes/lara-light-blue/theme.css",
    "primeflex/primeflex.css",
    "primeicons/primeicons.css",
  ],
  vite: {
    plugins: [svgLoader()],
    css: {
      preprocessorOptions: {
        scss: {
          additionalData:
            '@import "@/assets/styles/_functions.scss";@import "@/assets/styles/_variables.scss";@import "@/assets/styles/_mixins.scss";',
        },
      },
    },
  },
  build: {
    transpile: ["primevue"],
  },
  watch: [
    "src/assets/styles/_functions.scss",
    "src/assets/styles/_variables.scss",
    "src/assets/styles/_mixins.scss",
  ],
  // ssr: false
});
