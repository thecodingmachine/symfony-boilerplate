<template>
  <b-nav vertical pills>
    <b-row class="justify-content-center">
      <b-img
        :src="
          user.profilePicture !== null
            ? userProfilePictureURL(user.profilePicture)
            : defaultProfilePictureWhiteURL
        "
        rounded="circle"
        style="width: 5rem; height: 5rem; border: 1px solid white"
        :alt="$t('common.user.profile_picture')"
      ></b-img>
    </b-row>
    <b-row class="justify-content-center mt-2 text-white">
      {{ user.firstName + ' ' + user.lastName }}
    </b-row>
    <b-row class="justify-content-center mt-1">
      <b-badge pill :variant="roleColorVariantFromEnum(user.role)">{{
        roleTranslationFromEnum(user.role)
      }}</b-badge>
    </b-row>
    <b-row class="justify-content-center mt-1 mb-3 text-white">
      <b-link :to="localePath({ name: 'dashboard-my-profile' })">{{
        $t('common.nav.my_profile')
      }}</b-link>
    </b-row>
    <b-nav-item
      :to="localePath({ name: 'dashboard' })"
      :active="$route.path === localePath({ name: 'dashboard' })"
      >{{ $t('common.nav.dashboard') }}</b-nav-item
    >
    <div v-if="isGranted(ADMINISTRATOR)">
      <b-nav-text>{{ $t('common.nav.administration') }}</b-nav-text>
      <b-nav-item
        :to="localePath({ name: 'dashboard-admin-users' })"
        :active="$route.path === localePath({ name: 'dashboard-admin-users' })"
        >{{ $t('common.nav.users') }}</b-nav-item
      >
    </div>
  </b-nav>
</template>

<script>
import { Auth } from '@/mixins/auth'
import { Roles } from '@/mixins/roles'
import { Images } from '@/mixins/images'

export default {
  mixins: [Auth, Roles, Images],
}
</script>
