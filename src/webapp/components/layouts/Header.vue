<template>
  <b-navbar toggleable="lg" type="light" variant="light">
    <b-navbar-brand :to="localePath({ name: 'index' })">
      <img
        :src="logoImageURL"
        class="d-inline-block align-middle"
        style="width: 2.5rem; height: 2.5rem"
        alt="Logo"
      />
      {{ appName }}</b-navbar-brand
    >

    <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

    <b-collapse id="nav-collapse" is-nav>
      <b-navbar-nav>
        <b-nav-item
          v-if="isAuthenticated"
          :to="localePath({ name: 'dashboard' })"
          :active="$route.path === localePath({ name: 'dashboard' })"
        >
          {{ $t('common.nav.dashboard') }}
        </b-nav-item>
      </b-navbar-nav>

      <b-navbar-nav class="ml-auto">
        <b-nav-item-dropdown v-if="isAuthenticated" right>
          <template #button-content>
            <b-img
              :src="
                user.profilePicture !== null
                  ? userProfilePictureURL(user.profilePicture)
                  : defaultProfilePictureURL
              "
              rounded="circle"
              class="align-middle"
              style="width: 1.7rem; height: 1.7rem; border: 1px solid black"
              :alt="$t('common.user.profile_picture')"
            ></b-img
            >&nbsp;
            {{ user.firstName + ' ' + user.lastName }}
          </template>
          <b-dropdown-item :to="localePath({ name: 'dashboard-my-profile' })">
            {{ $t('common.nav.my_profile') }}
          </b-dropdown-item>
          <b-dropdown-item @click="logout">
            {{ $t('common.nav.logout') }}
          </b-dropdown-item>
        </b-nav-item-dropdown>
        <b-nav-item
          v-if="!isAuthenticated"
          right
          :to="localePath({ name: 'login' })"
          :active="$route.path === localePath({ name: 'login' })"
        >
          {{ $t('common.nav.login') }}
        </b-nav-item>
        <b-nav-item-dropdown right>
          <template #button-content>
            {{ currentLocale }}
          </template>
          <b-dropdown-item
            v-for="locale in availableLocales"
            :key="locale.code"
            :active="locale.code === currentLocale"
            :to="switchLocalePath(locale.code)"
          >
            {{ locale.code }}
          </b-dropdown-item>
        </b-nav-item-dropdown>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</template>

<script>
import { LogoutMutation } from '@/graphql/auth/logout.mutation'
import { Auth } from '@/mixins/auth'
import { Images } from '@/mixins/images'

export default {
  mixins: [Auth, Images],
  data() {
    return {
      appName: this.$config.appName,
    }
  },
  computed: {
    availableLocales() {
      return this.$i18n.locales.filter((i) => i.code !== this.$i18n.locale)
    },
    currentLocale() {
      return this.$i18n.locale
    },
  },
  methods: {
    async logout() {
      await this.$graphql.request(LogoutMutation)
      this.resetUser()
      this.$router.push(this.localePath({ name: 'login' }))
    },
  },
}
</script>
