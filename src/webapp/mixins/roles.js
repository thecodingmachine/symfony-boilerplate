import { ADMINISTRATOR, USER } from '@/enums/roles'

export const Roles = {
  data() {
    return {
      // Enums to use in your component.
      ADMINISTRATOR,
      USER,
    }
  },
  methods: {
    // Returns options' values for Bootstrap dropdowns.
    rolesAsSelectOptions(isForSearch = false) {
      return [
        {
          value: null,
          text: isForSearch
            ? this.$t('common.all')
            : this.$t('common.user.role.select'),
        },
        {
          value: ADMINISTRATOR,
          text: this.$t('common.user.role.administrator'),
        },
        { value: USER, text: this.$t('common.user.role.user') },
      ]
    },
    // Returns the translation from a enum value.
    roleTranslationFromEnum(role) {
      return this.$t('common.user.role.' + role.toLowerCase())
    },
    // Returns a color variant (Bootstrap) according
    // to a enum value.
    roleColorVariantFromEnum(role) {
      switch (role) {
        case ADMINISTRATOR:
          return 'primary'
        case USER:
          return 'secondary'
        default:
          return 'light'
      }
    },
  },
}
