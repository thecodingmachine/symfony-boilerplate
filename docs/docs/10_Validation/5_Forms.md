---
title: Forms
slug: /validation/forms
---

In the previous chapters, we saw how to centralize the validation in the API.
The next step is to integrate this validation mechanism with the web application.

Let's see how to do that! 😊

:::note

📣&nbsp;&nbsp;The Symfony Boilerplate uses [BootstrapVue](https://bootstrap-vue.org/) as templating framework.
However, most of the explanations from this chapter should work with most frameworks with little adjustments.

:::

## Browser Validation

Event if most of the validation occurs in the API, there are some rules that are better to validate directly in the
browser.

For instance:

```html title="Vue component <template> block"
<b-form-input
  type="text"
  autofocus
  trim
  required
/>
```

Here there are three rules:

1. The browser focuses this input first (UI logic).
2. The browser trims the input's content.
3. The browser does not allow to submit the form until this input has content.

Most of the time, that's all you have to do on the browser side.

## API Validation

The Symfony Boilerplate provides the `Form` mixin. This mixin brings everything you need for integrating
the API validation in your UI.

:::note

📣&nbsp;&nbsp;A mixin content merges with the content of your Vue component.

:::

```js title="Vue component <script> block"
import { Form } from '@/mixins/form'

export default {
  mixins: [Form],
}
```

Most of the logic occurs in the `onSubmit` method from your component:

```js title="Vue component <script> block"
import { Form } from '@/mixins/form'
import { UpdateEmailMutation } from '@/graphql/examples/update_email.mutation'

export default {
  mixins: [Form],
  data() {
    return {
      form: {
        email: '',
      },
    }
  },
  methods: {
    async onSubmit() {
      // We reset the form errors on submit.
      // This method comes from the Form mixin.
      this.resetFormErrors()

      try {
        const result = await this.$graphql.request(UpdateEmailMutation, {
          email: this.form.email
        })

        // Your UI logic on success.
        // ...
      } catch (e) {
        // If the API returns an error response (400 HTTP code),
        // we hydrate the Form mixin with its content. 
        // This method comes from the Form mixin.
        this.hydrateFormErrors(e)
      }
    },
  },
}
```

:::note

📣&nbsp;&nbsp;If the error response is not about validation (i.e., 400 HTTP code), the `hydrateFromErrors` method
throws the error back. The *src/webapp/layouts/error.vue* component will then catch it. 

:::

Now, we have to display the validation errors to the user. Thanks to [BootstrapVue](https://bootstrap-vue.org/) and
the `Form` mixin, it can be done quite easily:

```html title="Vue component <template> block"
<b-form @submit.stop.prevent="onSubmit">
  <b-form-group
    id="input-group-email"
    :label="$t('common.email.label_required')"
    label-for="input-email"
  >
    <b-form-input
      id="input-email"
      v-model="form.email"
      type="text"
      :placeholder="$t('common.email.placeholder')"
      autofocus
      trim
      required
      :state="formState('email')"
    />
    <b-form-invalid-feedback :state="formState('email')">
      <ErrorsList :errors="formErrors('email')" />
    </b-form-invalid-feedback>
  </b-form-group>
  <b-button type="submit" variant="primary">
    {{ $t('common.submit') }}
  </b-button>
</b-form>
```

* `<b-form @submit.stop.prevent="onSubmit">` binds the `onSubmit` method to this form.
* The `:state` attribute from the `<b-form-input>` component displays the input in red in case of error.
* The `<b-form-invalid-feedback>` component also uses the `:state` attribute. It works like a `v-if` to display or not
a list of errors related to the previous input.
* The Symfony Boilerplate provides the `<ErrorsList>` component.
* The `formState` method returns the current state of a given GraphQL key (see below for more details).
* The `formErrors` method returns the list of errors of a given GraphQL key (see below for more details).
* Both `formState` and `formErrors` methods come from the `Form` mixin.

A GraphQL key is either:

* The GraphQL field name if `InvalidModel`.
* The upload's directory name if `InvalidStorable`.
* The PHP argument name if it's a [GraphQLite](https://graphqlite.thecodingmachine.io/) annotation.

## Loading State

Most of the time, you want to display a loader or make your form read-only when the user is submitting the form.

The Symfony Boilerplate provides the `GlobalOverlay` mixin for that task:

```js title="Vue component <script> block"
import { GlobalOverlay } from '@/mixins/global-overlay'
import { Form } from '@/mixins/form'
import { UpdateEmailMutation } from '@/graphql/examples/update_email.mutation'

export default {
  mixins: [Form, GlobalOverlay],
  data() {
    return {
      form: {
        email: '',
      },
    }
  },
  methods: {
    async onSubmit() {
      this.resetFormErrors()
      // Displays the full page loader.
      // This method comes from the GlobalOverlay mixin.
      this.displayGlobalOverlay()

      try {
        const result = await this.$graphql.request(UpdateEmailMutation, {
          email: this.form.email
        })

        // Your UI logic on success.
        // ...
      } catch (e) {
        this.hydrateFormErrors(e)
      } finally {
        // Hides the full page loader.
        // This method comes from the GlobalOverlay mixin.
        this.hideGlobalOverlay()
      }
    },
  },
}
```

:::note

📣&nbsp;&nbsp;All layouts from the Symfony Boilerplate works with this mixin. If you add a custom layout, make sure
it integrates well with the `GlobalOverlay` mixin.

:::