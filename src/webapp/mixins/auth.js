import { mapGetters, mapMutations, mapState } from 'vuex'

export const Auth = {
  computed: {
    ...mapState('auth', ['user']),
    ...mapGetters('auth', ['isAuthenticated', 'isGranted']),
  },
  methods: {
    ...mapMutations('auth', [
      'setUser',
      'setUserLocale',
      'setUserProfilePicture',
      'resetUser',
    ]),
  },
}
