<template>
  <div>
    <Header />
    <b-overlay
      :show="isGlobalOverlayActive"
      spinner-variant="primary"
      rounded="sm"
    >
      <b-container fluid>
        <b-row>
          <b-col md="3" class="vh-100 pt-3 d-none d-md-block left-menu">
            <LeftMenu />
          </b-col>
          <b-sidebar
            id="left-menu-mobile"
            sidebar-class="left-menu d-md-none d-lg-none d-xl-none text-center"
            text-variant="light"
            is-nav
          >
            <LeftMenu />
          </b-sidebar>
          <b-col class="vh-100 p-3">
            <Nuxt />
          </b-col>
        </b-row>
      </b-container>
      <b-button
        v-b-toggle:left-menu-mobile
        class="left-menu-button-mobile d-md-none d-lg-none d-xl-none"
      >
        <b-icon icon="list"> </b-icon>
      </b-button>
    </b-overlay>
  </div>
</template>

<script>
import { GlobalOverlay } from '@/mixins/global-overlay'
import LeftMenu from '@/components/layouts/LeftMenu'
import Header from '@/components/layouts/Header'

export default {
  components: { Header, LeftMenu },
  mixins: [GlobalOverlay],
}
</script>
