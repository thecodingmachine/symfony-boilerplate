require("@rushstack/eslint-patch/modern-module-resolution")
module.exports = {
    root: true,
    extends: [
        "@nuxt/eslint-config",
     //   "plugin:vue/vue3-recommended"
    ],
    rules: {
        'semi': ["error", "always"]
    }
};