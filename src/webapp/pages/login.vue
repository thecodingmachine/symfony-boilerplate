<template>
  <b-card>
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
        />
      </b-form-group>
      <b-form-group
        id="input-group-password"
        :label="$t('pages.login.password.label_required')"
        label-for="input-password"
      >
        <b-form-input
          id="input-password"
          v-model="form.password"
          type="password"
          :placeholder="$t('pages.login.password.placeholder')"
          trim
          required
        />
      </b-form-group>
      <b-form-invalid-feedback :state="formState('Security')" class="mb-3">
        {{ $t('pages.login.error_message') }}
      </b-form-invalid-feedback>
      <b-button type="submit" variant="primary" class="card-link">
        {{ $t('common.nav.login') }}
      </b-button>
      <b-link
        class="card-link"
        :to="
          localePath({ name: 'reset-password', query: { email: form.email } })
        "
        >{{ $t('pages.login.forgot_password') }}</b-link
      >
    </b-form>
  </b-card>
</template>

<script>
import { GlobalOverlay } from '@/mixins/global-overlay'
import { Form } from '@/mixins/form'
import { UpdateLocaleMutation } from '@/graphql/auth/update_locale.mutation'
import { LoginMutation } from '@/graphql/auth/login.mutation'
import { Auth } from '@/mixins/auth'

export default {
  mixins: [Auth, Form, GlobalOverlay],
  layout: 'card',
  middleware: ['redirect-if-authenticated'],
  data() {
    return {
      form: {
        email: this.$route.query.email || '',
        password: '',
      },
      redirect: this.$route.query.redirect || '',
    }
  },
  methods: {
    async onSubmit() {
      this.resetFormErrors()
      this.displayGlobalOverlay()

      try {
        const result = await this.$graphql.request(LoginMutation, {
          userName: this.form.email,
          password: this.form.password,
        })

        // Update user's locale if different from the
        // web application locale.
        if (result.login.locale !== this.$i18n.locale) {
          await this.$graphql.request(UpdateLocaleMutation, {
            locale: this.$i18n.locale.toUpperCase(),
          })

          result.login.locale = this.$i18n.locale
        }

        this.setUser(result.login)

        if (this.redirect !== '') {
          this.$router.push(this.redirect)
        } else {
          this.$router.push(this.localePath({ name: 'dashboard' }))
        }
      } catch (e) {
        this.hydrateFormErrors(e, true)
      } finally {
        this.hideGlobalOverlay()
      }
    },
  },
}
</script>
