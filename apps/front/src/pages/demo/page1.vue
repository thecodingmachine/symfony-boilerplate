<template>
  <div>
    Page 1
    <img src="~/assets/images/panda.webp" :alt="$t('pages.page1.altPanda')" />
    <div class="panda-background">{{ $t("pages.page1.background") }}</div>
  </div>
</template>
<script lang="ts" setup>
logger.info("page 1");
</script>
<style lang="scss" scoped>
.panda-background {
  color: $white;
  width: 200px;
  height: 200px;
  background-image: url("~/assets/images/true-panda.jpeg");
  background-size: contain;
  background-repeat: no-repeat;
}
</style>
