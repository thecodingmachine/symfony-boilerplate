<template>
  <h1>{{ $t('common.nav.dashboard') }}</h1>
</template>

<script>
export default {
  layout: 'dashboard',
  middleware: ['redirect-if-not-authenticated'],
}
</script>
