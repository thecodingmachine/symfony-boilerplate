<template>
  <div>
    <Header />
    <b-overlay
      :show="isGlobalOverlayActive"
      spinner-variant="primary"
      rounded="sm"
    >
      <b-container class="mt-3">
        <Nuxt />
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
