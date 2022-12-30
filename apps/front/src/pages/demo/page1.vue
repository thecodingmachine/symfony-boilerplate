<template>
  <div>
    Page 1
    <img src="~/assets/images/panda.webp" alt="Discover Pandas" />
    <div class="panda-background">
      This is to discover background scss
    </div>
  </div>
</template>
<script lang="ts" setup>
logger.info('page 1');
</script>
<style lang="scss" scoped>
.panda-background {
  color: $white;
  width: 200px;
  height: 200px;
  background-image: url('~/assets/images/true-panda.jpeg');
  background-size: contain;
  background-repeat: no-repeat;
}
</style>
