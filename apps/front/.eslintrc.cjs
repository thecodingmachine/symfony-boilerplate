require("@rushstack/eslint-patch/modern-module-resolution")
module.exports = {
    root: true,
    extends: [
        "plugin:vue/vue3-recommended",
        "@nuxtjs/eslint-config-typescript",
        "@vue/eslint-config-airbnb-with-typescript"
    ],
    rules: {
        'semi': ["error", "always"],
        'comma-dangle': "off", // covered by eslint-config-airbnb-with-typescript
        'space-before-function-paren': "off" // covered by eslint-config-airbnb-with-typescript
    }
};