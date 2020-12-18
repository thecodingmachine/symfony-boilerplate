<template>
  <b-card>
    <b-form
      v-if="!success && !hasTokenValidationFailed"
      @submit.stop.prevent="onSubmit"
    >
      <b-form-group
        id="input-group-new-password"
        :label="$t('pages.update_password.new_password.label_required')"
        label-for="input-new-password"
      >
        <b-form-input
          id="input-new-password"
          v-model="form.newPassword"
          type="password"
          :placeholder="$t('pages.update_password.new_password.placeholder')"
          autofocus
          trim
          required
          :state="formState('newPassword')"
        />
        <b-form-invalid-feedback :state="formState('newPassword')">
          <ErrorsList :errors="formErrors('newPassword')" />
        </b-form-invalid-feedback>
      </b-form-group>
      <b-form-group
        id="input-group-password-confirmation"
        :label="
          $t('pages.update_password.password_confirmation.label_required')
        "
        label-for="input-password-confirmation"
      >
        <b-form-input
          id="input-password-confirmation"
          v-model="form.passwordConfirmation"
          type="password"
          :placeholder="
            $t('pages.update_password.password_confirmation.placeholder')
          "
          trim
          required
          :state="formState('passwordConfirmation')"
        />
        <b-form-invalid-feedback :state="formState('passwordConfirmation')">
          <ErrorsList :errors="formErrors('passwordConfirmation')" />
        </b-form-invalid-feedback>
      </b-form-group>
      <b-button type="submit" variant="primary">
        {{ $t('common.update') }}
      </b-button>
    </b-form>
    <div v-else-if="!success && hasTokenValidationFailed" class="text-center">
      <p>
        <b-icon icon="exclamation-circle" variant="primary" font-scale="4">
        </b-icon>
      </p>
      <p>{{ $t('pages.update_password.invalid_token_message') }}</p>

      <b-link class="card-link" :to="localePath({ name: 'reset-password' })">{{
        $t('common.retry')
      }}</b-link>
    </div>
    <div v-else class="text-center">
      <p>
        <b-icon icon="check-circle" variant="primary" font-scale="4"> </b-icon>
      </p>
      <p>{{ $t('pages.update_password.success_message') }}</p>

      <b-link class="card-link" :to="localePath({ name: 'login' })">{{
        $t('common.nav.login')
      }}</b-link>
    </div>
  </b-card>
</template>

<script>
import ErrorsList from '@/components/forms/ErrorsList'
import { GlobalOverlay } from '@/mixins/global-overlay'
import { Form } from '@/mixins/form'
import { VerifyResetPasswordTokenMutation } from '@/graphql/auth/verify_reset_password_token.mutation'
import { UpdatePasswordMutation } from '@/graphql/auth/update_password.mutation'

export default {
  components: { ErrorsList },
  mixins: [Form, GlobalOverlay],
  layout: 'card',
  middleware: ['redirect-if-authenticated'],
  async asyncData(context) {
    try {
      await context.app.$graphql.request(VerifyResetPasswordTokenMutation, {
        resetPasswordTokenId: context.params.id,
        plainToken: context.params.token,
      })
    } catch (e) {
      return {
        hasAsyncTokenValidationFailed: true,
      }
    }
  },
  data() {
    return {
      form: {
        newPassword: '',
        passwordConfirmation: '',
      },
      success: false,
      hasAsyncTokenValidationFailed: false,
      email: '',
    }
  },
  computed: {
    hasTokenValidationFailed() {
      return (
        this.hasAsyncTokenValidationFailed ||
        this.hasFormErrors('verifyResetPasswordToken')
      )
    },
  },
  methods: {
    async onSubmit() {
      this.resetFormErrors()
      this.displayGlobalOverlay()

      try {
        const result = await this.$graphql.request(UpdatePasswordMutation, {
          resetPasswordTokenId: this.$route.params.id,
          plainToken: this.$route.params.token,
          newPassword: this.form.newPassword,
          passwordConfirmation: this.form.passwordConfirmation,
        })

        this.success = true
        this.email = result.updatePassword.email
      } catch (e) {
        this.hydrateFormErrors(e)
      } finally {
        this.hideGlobalOverlay()
      }
    },
  },
}
</script>
