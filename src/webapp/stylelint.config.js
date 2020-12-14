module.exports = {
  extends: ['stylelint-config-standard', 'stylelint-config-prettier'],
  // add your custom config here
  // https://stylelint.io/user-guide/configuration
  rules: {
    // So that stylelint accepts @include.
    'at-rule-no-unknown': null,
  },
}
