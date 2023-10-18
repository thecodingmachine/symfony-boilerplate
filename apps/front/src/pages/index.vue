<template>
  <UContainer class="container columns-2">
    <UCard>
      <h2 class="text-lg">
        {{ $t('pages.dashboard.score.title') }}
        <span v-if="authStore" class="font-bold text-green-600/100">
          {{ authStore.me?.score || $t('pages.dashboard.score.defaultScore') }}
        </span>
      </h2>
    </UCard>
    <UCard>
      <h2 class="text-lg">
        {{ $t('pages.dashboard.pendingOperations.title') }}
        <span class="font-bold text-green-600/100">
          {{ authStore.me?.payments_pending || $t('pages.dashboard.pendingOperations.defaultPending') }}
        </span>
      </h2>
    </UCard>
  </UContainer>
</template>

<script setup lang="ts">
import { useAuthUser } from "~/store/auth";

const authStore = useAuthUser();
</script>
