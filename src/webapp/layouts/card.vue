<template>
  <div>
    <Header />
    <b-overlay
      :show="isGlobalOverlayActive"
      spinner-variant="primary"
      rounded="sm"
    >
      <b-container
        class="d-flex align-items-center justify-content-center mt-5"
      >
        <div style="width: 90%; max-width: 450px">
          <Nuxt />
        </div>
      </b-container>
    </b-overlay>
  </div>
</template>

<script>
import Header from '@/components/layouts/Header'
import { GlobalOverlay } from '@/mixins/global-overlay'

export default {
  components: { Header },
  mixins: [GlobalOverlay],
}
</script>
