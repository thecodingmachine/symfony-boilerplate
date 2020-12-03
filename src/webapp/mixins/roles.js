import { ADMINISTRATOR, CLIENT, MERCHANT } from '@/enums/roles'

export const Roles = {
  data() {
    return {
      // Enums to use in your component.
      ADMINISTRATOR,
      MERCHANT,
      CLIENT,
    }
  },
  methods: {
    // Returns options' values for Bootstrap dropdowns.
    rolesAsSelectOptions(isForSearch = true) {
      return [
        {
          value: null,
          text: isForSearch
            ? this.$t('common.all')
            : this.$t('common.roles.select'),
        },
        { value: ADMINISTRATOR, text: this.$t('common.roles.administrator') },
        { value: MERCHANT, text: this.$t('common.roles.merchant') },
        { value: CLIENT, text: this.$t('common.roles.client') },
      ]
    },
  },
}
