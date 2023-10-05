<template>
  <TieredMenu :model="items" class="h-screen sticky w-full"> </TieredMenu>
</template>
<script setup lang="ts">
const items = computed(() => [
  {
    label: "Users",
    icon: "pi pi-fw pi-file",
    to: "/users",
  },
  {
    label: "Page1",
    icon: "pi pi-fw pi-pencil",
    to: "/demo/page1",
  },
  {
    label: "Page2",
    icon: "pi pi-fw pi-pencil",
    to: "/demo/page2",
  },
  {
    label: "Page3",
    icon: "pi pi-fw pi-pencil",
    to: "/demo/page3",
  },
  {
    separator: true,
  },
  {
    label: "Quit",
    icon: "pi pi-fw pi-power-off",
  },
]);
</script>
