<template>
  <div class="row justify-content-end py-2 mb-2">
    <div class="col-1">
      <select @change="onLocalChanged">
        <option
          v-for="availableLocale in locales"
          :selected="availableLocale.code === locale"
          :value="availableLocale.code"
        >{{ availableLocale.name }}</option>
      </select>
    </div>
    <div class="col-1">
      <NuxtLink to="/auth/register">Register</NuxtLink>
    </div>
    <div class="col-2">
      <p v-if="email">
        {{ $t('global.welcome') }} {{ email }}
      </p>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useAuthUser } from '~/store/auth';
import { useI18n } from "#imports";

const { locale, locales, setLocale } = useI18n();
const authStore = useAuthUser();
const router = useRouter();

const email = authStore.authUser?.email || '';

const onLocalChanged = (event: Event) => {
  setLocale(event.target!.value);
}
</script>
