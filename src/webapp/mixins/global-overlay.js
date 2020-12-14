import { mapGetters, mapMutations } from 'vuex'

export const GlobalOverlay = {
  computed: {
    ...mapGetters('global-overlay', ['isGlobalOverlayActive']),
  },
  methods: {
    ...mapMutations('global-overlay', [
      'displayGlobalOverlay',
      'hideGlobalOverlay',
    ]),
  },
}
