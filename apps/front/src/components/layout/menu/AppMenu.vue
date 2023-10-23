<template>
  <TieredMenu :model="items" class="h-screen sticky w-full"> </TieredMenu>
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
    to: "/users",
  },
  {
    label: t("components.layout.menu.appMenu.companies"),
    icon: "pi pi-fw pi-file",
    to: "/companies",
  },
  {
    label: t("components.layout.menu.appMenu.page1"),
    icon: "pi pi-fw pi-pencil",
    to: "/demo/page1",
  },
  {
    label: t("components.layout.menu.appMenu.page2"),
    icon: "pi pi-fw pi-pencil",
    to: "/demo/page2",
  },
  {
    label: t("components.layout.menu.appMenu.page3"),
    icon: "pi pi-fw pi-pencil",
    to: "/demo/page3",
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
