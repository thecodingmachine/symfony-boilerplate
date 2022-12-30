<template>
  <div
    class="d-flex flex-sm-column flex-row flex-nowrap bg-light align-items-center sticky-top"
  >
    <NuxtLink to="/" class="d-block p-3 link-dark text-decoration-none">
      <icons1-square-fill />
    </NuxtLink>
    <ul
      class="nav nav-pills nav-flush flex-sm-column flex-row flex-nowrap mb-auto mx-auto text-center align-items-center"
    >
      <MenuItem to="/demo/page1">
        <icons2-square-fill class="align-center" />
        <span class="mx-2"> This is the item 1</span>
      </MenuItem>
      <MenuItem to="/demo/page2">
        <icons3-square-fill />
        <span class="mx-2"> This is the item 2</span>
      </MenuItem>
      <MenuItem to="/demo/page3">
        <icons4-square-fill />
        <span class="mx-2"> This is the item 3</span>
      </MenuItem>
      <MenuItem to="/demo/page4">
        <icons5-square-fill />
        <span class="mx-2"> This is the item 4</span>
      </MenuItem>
      <MenuItem to="/demo/page5">
        <icons6-square-fill />
        <span class="mx-2"> This is the item 5</span>
      </MenuItem>
    </ul>
  </div>
</template>
<script setup lang="ts">
// Credit to https://dev.to/codeply/bootstrap-5-sidebar-examples-38pb
// Another interesting ressource https://www.codeply.com/p/yE87h7irNi/icons-text
import MenuItem from './MenuItem.vue';
</script>
