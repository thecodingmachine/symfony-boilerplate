const appendError = (array = [], error) => [...array, error]

export const Form = {
  data() {
    return {
      // Internal usage.
      allFormErrors: {},
    }
  },
  methods: {
    // Resets the form's errors.
    // You should call it in your onSubmit() implementation.
    resetFormErrors() {
      this.allFormErrors = {}
    },
    // Takes GraphQL errors and hydrates mixin errors.
    // You should call it in your catch statement from
    // your onSubmit() implementation.
    hydrateFormErrors(e, isLogin = false) {
      if (
        typeof e === 'undefined' ||
        typeof e.response === 'undefined' ||
        typeof e.response.errors === 'undefined' ||
        (isLogin && e.response.status !== 401) ||
        (!isLogin && e.response.status !== 400)
      ) {
        // The error must be handled by our "error" layout.
        // We do not call this.$nuxt.error(e) here
        // because for some reasons it does not works.
        throw e
      }

      e.response.errors.forEach((error) => {
        if (typeof error.extensions.field !== 'undefined') {
          this.allFormErrors = {
            ...this.allFormErrors,
            [error.extensions.field]: appendError(
              this.allFormErrors[error.extensions.field],
              error.message
            ),
          }
        } else {
          this.allFormErrors = {
            ...this.allFormErrors,
            [error.extensions.category]: appendError(
              this.allFormErrors[error.extensions.category],
              error.message
            ),
          }
        }
      })
    },
    // Returns the Bootstrap's form's state if the
    // GraphQL key has an error or not.
    formState(key) {
      return typeof this.allFormErrors[key] === 'undefined' ? null : false
    },
    // Returns true if the GraphQL key has an error.
    hasFormErrors(key) {
      return typeof this.allFormErrors[key] !== 'undefined'
    },
    // Returns all errors from a GraphQL key.
    formErrors(key) {
      return this.allFormErrors[key] || []
    },
    onSubmit() {
      // To implement in your component.
    },
  },
}
