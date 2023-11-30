<template>
  <div class="surface-ground">
    <div class="grid">
      <div class="col-12">
        <layout-app-header />
      </div>
    </div>
    <div class="grid">
      <div class="col-2">
        <layout-menu-app-menu />
      </div>
      <div class="col min-h-screen">
        <slot />
      </div>
    </div>
  </div>
</template>
<script setup></script>
