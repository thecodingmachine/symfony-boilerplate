import { ADMINISTRATOR, CLIENT, MERCHANT } from '@/enums/roles'

function level(role) {
  switch (role) {
    case ADMINISTRATOR:
      return 3
    case MERCHANT:
      return 2
    case CLIENT:
      return 1
    default:
      return 0
  }
}

export default {
  isAuthenticated(state) {
    return state.user.email !== ''
  },
  isGranted: (state) => (role) => {
    return level(state.user.role) >= level(role)
  },
}
