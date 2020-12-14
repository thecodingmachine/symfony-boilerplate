import { FR, EN } from '@/enums/locales'

export const Locales = {
  data() {
    return {
      // Enums to use in your component.
      FR,
      EN,
    }
  },
  methods: {
    // Returns options' values for Bootstrap dropdowns.
    localesAsSelectOptions(isForSearch = false) {
      return [
        {
          value: null,
          text: isForSearch
            ? this.$t('common.all')
            : this.$t('common.user.locale.select'),
        },
        { value: FR, text: 'fr' },
        { value: EN, text: 'en' },
      ]
    },
  },
}
