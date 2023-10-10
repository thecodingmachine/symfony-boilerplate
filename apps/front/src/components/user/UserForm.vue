<template>
  <div>
    <label for="email">{{ $t("components.user.form.email") }}</label>
    <input
      type="text"
      :value="email"
      @input="$emit('update:email', clearInput($event))"
    />
  </div>
  <div>
    <label for="password">{{ $t("components.user.form.password") }}</label>
    <input
      name="password"
      type="password"
      :value="password"
      @input="$emit('update:password', clearInput($event))"
    />
  </div>
  <div>
    <label for="passwordConfirm">{{
      $t("components.user.form.passwordConfirm")
    }}</label>
    <input
      name="passwordConfirm"
      type="password"
      :value="passwordConfirm"
      @input="$emit('update:passwordConfirm', clearInput($event))"
    />
    <span v-if="!isPasswordConfirmed" class="text-danger">
      {{ $t("components.user.form.errorPasswordConfirm") }}
    </span>
  </div>
</template>
<script setup lang="ts">
import { User } from "~/types/User";

defineProps<
  Omit<User, "id"> & {
    isPasswordConfirmed: boolean;
    password: string;
    passwordConfirm: string;
  }
>();

interface EventEmitter {
  (e: "update:email", email: string): void;
  (e: "update:password", password: string): void;
  (e: "update:passwordConfirm", passwordConfirm: string): void;
}

defineEmits<EventEmitter>();

const clearInput = (inputEvent: Event) => {
  return (inputEvent.target as HTMLInputElement)?.value || "";
};
</script>

<style scoped lang="scss"></style>
