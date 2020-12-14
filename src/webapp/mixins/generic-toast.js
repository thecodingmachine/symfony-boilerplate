export const GenericToast = {
  methods: {
    genericSuccessToast() {
      this.$toast.show(this.$t('mixins.generic_toast.success_message'))
    },
  },
}
