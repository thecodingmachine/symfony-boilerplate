import { MeQuery } from '@/graphql/auth/me.query'

export default {
  async me({ commit }) {
    const result = await this.app.$graphql.request(MeQuery)

    if (result.me) {
      commit('setUser', result.me)
    } else {
      commit('resetUser')
    }
  },
}
