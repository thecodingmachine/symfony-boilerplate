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
          v-if="isGranted(ADMINISTRATOR)"
          :to="localePath({ name: 'admin-users' })"
          :active="$route.path === localePath({ name: 'admin-users' })"
        >
          {{ $t('components.layouts.header.administration') }}
        </b-nav-item>
      </b-navbar-nav>

      <b-navbar-nav class="ml-auto">
        <b-nav-item-dropdown v-if="isAuthenticated" right>
          <template #button-content>
            <em>{{ user.firstName + ' ' + user.lastName }}</em>
          </template>
          <b-dropdown-item href="#" @click="logout">{{
            $t('common.logout')
          }}</b-dropdown-item>
        </b-nav-item-dropdown>
        <b-nav-item
          v-if="!isAuthenticated"
          right
          :to="localePath({ name: 'login' })"
          :active="$route.path === localePath({ name: 'login' })"
          >{{ $t('common.login') }}</b-nav-item
        >
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
import logo from '@/assets/images/logo.svg'
import { Roles } from '@/mixins/roles'
import { LogoutMutation } from '@/graphql/auth/logout.mutation'
import { Auth } from '@/mixins/auth'

export default {
  mixins: [Auth, Roles],
  data() {
    return {
      appName: this.$config.appName,
      logoImageURL: logo,
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
