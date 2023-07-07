<template>
  <Toolbar>
    <template #start>
      <NuxtLink to="/">
        <h1 class="">Boilerplate</h1>
      </NuxtLink>
    </template>

    <template #end>
      <select @change="onLocalChanged" class="mr-2">
        <option
          v-for="availableLocale in locales"
          :selected="availableLocale.code === locale"
          :value="availableLocale.code"
        >
          {{ availableLocale.name }}
        </option>
      </select>

      <NuxtLink to="/auth/register" class="mr-2">
        Register
      </NuxtLink>

      <p v-if="email">
        {{ $t('global.welcome') }} {{ email }}
      </p>
    </template>
  </Toolbar>
</template>
<script setup lang="ts">
import { useAuthUser } from '~/store/auth';
import { useI18n } from "#imports";
import Toolbar from 'primevue/toolbar';

const { locale, locales, setLocale } = useI18n();
const authStore = useAuthUser();
const router = useRouter();

const email = authStore.authUser?.email || '';

const onLocalChanged = (event: Event) => {
  setLocale(event.target!.value);
}
</script>

<style lang="scss" scoped>
.p-toolbar {
  background-color: var(--blue-300);
  border: unset;
  border-radius: unset;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  padding: 0 12px;
}
</style>
