<template>
  <TieredMenu :model="items" class="h-screen sticky w-full">
    <template #item="{ label, item, props, hasSubmenu }">
      <NuxtLink v-if="item.route" v-slot="routerProps" :to="item.route" custom>
        <a
          :href="routerProps.href"
          v-bind="props.action"
          @click="routerProps.navigate"
        >
          <span v-bind="props.icon" />
          <span v-bind="props.label">{{ label }}</span>
        </a>
      </NuxtLink>
      <a v-else :href="item.url" :target="item.target" v-bind="props.action">
        <span v-bind="props.icon" />
        <span v-bind="props.label">{{ label }}</span>
        <span
          v-if="hasSubmenu"
          class="pi pi-fw pi-angle-right"
          v-bind="props.submenuicon"
        />
      </a>
    </template>
  </TieredMenu>
</template>
<script setup lang="ts">
import useAuthUser from "~/store/auth";

const { t } = useI18n();
const authStore = useAuthUser();
const { $appFetch } = useNuxtApp();
const items = computed(() => [
  {
    label: t("components.layout.menu.appMenu.users"),
    icon: "pi pi-fw pi-file",
    route: "/users",
  },
  {
    label: t("components.layout.menu.appMenu.page1"),
    icon: "pi pi-fw pi-pencil",
    route: "/demo/page1",
  },
  {
    label: t("components.layout.menu.appMenu.page2"),
    icon: "pi pi-fw pi-pencil",
    route: "/demo/page2",
  },
  {
    label: t("components.layout.menu.appMenu.validation"),
    icon: "pi pi-fw pi-id-card",
    route: "/demo/validation",
  },
  {
    separator: true,
  },
  {
    label: t("components.layout.menu.appMenu.quit"),
    icon: "pi pi-fw pi-power-off",
    command: async () => {
      await authStore.logoutUser($appFetch);
      await navigateTo("/", { external: true });
    },
  },
]);
</script>
