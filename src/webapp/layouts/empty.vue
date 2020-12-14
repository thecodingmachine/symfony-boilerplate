<template>
  <b-overlay
    :show="isGlobalOverlayActive"
    spinner-variant="primary"
    rounded="sm"
  >
    <Nuxt />
  </b-overlay>
</template>

<script>
import { GlobalOverlay } from '@/mixins/global-overlay'

export default {
  mixins: [GlobalOverlay],
}
</script>
