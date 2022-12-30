<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <layout-app-header />
      </div>
    </div>
    <div class="row">
      <div class="col-sm-auto bg-light sticky-top vh-100 overflow-auto">
        <layout-menu-app-menu />
      </div>
      <div class="col-sm p-3 min-vh-100">
        <slot />
      </div>
    </div>
  </div>
</template>
