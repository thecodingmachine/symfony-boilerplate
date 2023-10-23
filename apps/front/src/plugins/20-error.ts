import type { BasicError } from "~/types/BasicError";

export default defineNuxtPlugin((nuxtApp) => {
  // Here you can handle your errors globally
  nuxtApp.hook("vue:error", (..._args) => {
    logger.error("vue:error", _args);
    // if (process.client) {
    //   console.log(..._args)
    // }
  });

  nuxtApp.hook("app:error", (..._args) => {
    logger.error("app:error");
    logger.error(_args);
    // if (process.client) {
    //   console.log(..._args)
    // }
  });

  nuxtApp.vueApp.config.errorHandler = (..._args) => {
    logger.info("global error handler");
    const err: BasicError = _args[0] as BasicError;
    logger.error(err);

    const t = nuxtApp.vueApp.config.globalProperties.$t;
    if (err.name !== "FetchError") {
      nuxtApp.vueApp.config.globalProperties.$toast.add({
        severity: "error",
        summary: t("plugins.error.toasterCatchAllSummary"),
        detail: t("plugins.error.toasterCatchAllDetail"),
        life: 3000,
      });
    }
    // if (process.client) {
    //   console.log(..._args)
    // }
  };
});
