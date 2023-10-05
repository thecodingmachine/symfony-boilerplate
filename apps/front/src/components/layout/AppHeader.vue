<template>
    <div class="grid">
        <div class="col-2 col-offset-8">
            <NuxtLink to="/auth/register">Register</NuxtLink>
        </div>
        <div class="col-2">
            <p v-if="username">Welcome {{ username }}</p>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useAuthUser } from "~/store/auth";

const authStore = useAuthUser();

const username = authStore.authUser?.username || "";
</script>