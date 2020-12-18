<template>
  <b-card>
    <b-form v-if="!success" @submit.stop.prevent="onSubmit">
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
      <b-button type="submit" variant="primary" class="card-link">
        {{ $t('common.send_email') }}
      </b-button>
      <b-link
        class="card-link"
        :to="localePath({ name: 'login', query: { email: form.email } })"
        >{{ $t('common.nav.login') }}</b-link
      >
    </b-form>
    <div v-else class="text-center">
      <p>
        <b-icon icon="check-circle" variant="primary" font-scale="4"> </b-icon>
      </p>
      <h5>
        {{ form.email }}
      </h5>
      <p>
        {{ $t('pages.reset_password.success_message') }}
      </p>

      <b-nav align="center">
        <b-nav-item>
          <b-link
            :to="localePath({ name: 'login', query: { email: form.email } })"
            >{{ $t('common.nav.login') }}</b-link
          >
        </b-nav-item>
        <b-nav-item>
          <b-link @click="resetForm">{{ $t('common.retry') }}</b-link>
        </b-nav-item>
      </b-nav>
    </div>
  </b-card>
</template>

<script>
import ErrorsList from '@/components/forms/ErrorsList'
import { GlobalOverlay } from '@/mixins/global-overlay'
import { Form } from '@/mixins/form'
import { ResetPasswordMutation } from '@/graphql/auth/reset_password.mutation'

export default {
  components: { ErrorsList },
  mixins: [Form, GlobalOverlay],
  layout: 'card',
  middleware: ['redirect-if-authenticated'],
  data() {
    return {
      form: {
        email: this.$route.query.email || '',
      },
      success: false,
    }
  },
  methods: {
    async onSubmit() {
      this.resetFormErrors()
      this.displayGlobalOverlay()

      try {
        await this.$graphql.request(ResetPasswordMutation, {
          email: this.form.email,
        })

        this.success = true
      } catch (e) {
        this.hydrateFormErrors(e)
      } finally {
        this.hideGlobalOverlay()
      }
    },
    resetForm() {
      this.success = false
      this.form.email = ''
    },
  },
}
</script>
