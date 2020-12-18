<template>
  <div>
    <b-container v-if="isGlobalOverlayActive" class="h-100"></b-container>
    <div v-else-if="!isGlobalOverlayActive && displayErrorHTML">
      <!-- eslint-disable-next-line vue/no-v-html -->
      <div v-html="error.response.error"></div>
    </div>
    <b-container
      v-else
      class="d-flex align-items-center justify-content-center mt-5"
    >
      <div style="width: 90%; max-width: 450px">
        <b-card class="text-center">
          <div v-if="error.statusCode === 404">
            <p>
              <b-icon icon="question-circle" variant="primary" font-scale="4">
              </b-icon>
            </p>
            <h5>
              {{ $t('layouts.error.not_found_message') }}
            </h5>
          </div>
          <div v-else-if="error.statusCode === 403">
            <p>
              <b-icon icon="x-circle" variant="primary" font-scale="4">
              </b-icon>
            </p>
            <h5>
              {{ $t('layouts.error.access_forbidden_message') }}
            </h5>
          </div>
          <div v-else>
            <p>
              <b-icon icon="bug" variant="primary" font-scale="4"> </b-icon>
            </p>
            <h5>
              {{ $t('layouts.error.generic_message') }}
            </h5>
          </div>
          <b-link class="card-link" :to="localePath({ name: 'index' })">{{
            $t('layouts.error.home_page')
          }}</b-link>
        </b-card>
      </div>
    </b-container>
  </div>
</template>

<script>
import { GlobalOverlay } from '@/mixins/global-overlay'
import { Auth } from '@/mixins/auth'

export default {
  mixins: [Auth, GlobalOverlay],
  layout: 'empty',
  props: {
    error: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      displayErrorHTML: false,
    }
  },
  mounted() {
    this.displayGlobalOverlay()

    if (this.error.statusCode === 401) {
      // Redirect the non-authenticated user to the
      // login page (with the information on where to
      // redirect him after login success).
      this.resetUser()

      this.hideGlobalOverlay()

      this.$router.push(
        this.localePath({
          name: 'login',
          query: {
            redirect: this.$route.fullPath,
          },
        })
      )

      return
    }

    if (
      typeof this.error.response !== 'undefined' &&
      typeof this.error.response.error !== 'undefined' &&
      process.env.NODE_ENV === 'development'
    ) {
      // The API returned an HTML error response we want to display
      // to the developer (only in development!).
      this.displayErrorHTML = true
      this.hideGlobalOverlay()

      return
    }

    // Everything else.
    this.hideGlobalOverlay()
  },
}
</script>
