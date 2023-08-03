<template>
  <div>
    <UserUpdateForm
      v-if="!!route.params.id.toString()"
      :user-id="route.params.id.toString()"
    />
    <div v-if="!route.params.id.toString()">Une erreur est survenue</div>
  </div>
</template>

<script setup lang="ts">
import UserUpdateForm from "~/components/user/UserUpdateForm.vue";

const route = useRoute();
</script>

<style scoped lang="scss"></style>
