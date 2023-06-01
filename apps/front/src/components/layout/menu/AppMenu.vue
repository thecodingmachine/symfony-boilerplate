<template>
  <div
    class="d-flex flex-sm-column flex-row flex-nowrap bg-light align-items-center sticky-top"
  >
    <NuxtLink to="/users">Users</NuxtLink>
    <NuxtLink to="/" class="d-block p-3 link-dark text-decoration-none">
      <Icon1Square />
    </NuxtLink>
    <ul
      class="nav nav-pills nav-flush flex-sm-column flex-row flex-nowrap mb-auto mx-auto text-center align-items-center"
    >
      <MenuItem to="/demo/page1">
        <Icon1Square />
        <span class="mx-2"> This is the item 1</span>
      </MenuItem>
      <MenuItem to="/demo/page2">
        <Icon2Square />
        <span class="mx-2"> This is the item 2</span>
      </MenuItem>
      <MenuItem to="/demo/page3">
        <Icon3Square />
        <span class="mx-2"> This is the item 3</span>
      </MenuItem>
    </ul>
  </div>
</template>
<script setup lang="ts">
// Credit to https://dev.to/codeply/bootstrap-5-sidebar-examples-38pb
// Another interesting ressource https://www.codeply.com/p/yE87h7irNi/icons-text
import MenuItem from './MenuItem.vue';
import Icon1Square from '~/assets/images/icon-1-square.svg?component';
import Icon2Square from '~/assets/images/icon-2-square.svg?component';
import Icon3Square from '~/assets/images/icon-3-square.svg?component';
</script>
