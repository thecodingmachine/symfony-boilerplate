<template>
  <li class="nav-item">
    <NuxtLink v-bind="$attrs" class="nav-link py-3 px-2">
      <slot />
    </NuxtLink>
  </li>
</template>
