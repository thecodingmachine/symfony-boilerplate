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
    <label for="profilePicture">Profile Picture</label>
    <input type="file" accept="image/*" @input="onSelectFile" />
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
    profilePictureFile: string | undefined;
  }
>();

interface EventEmitter {
  (e: "update:email", email: string): void;
  (e: "update:password", password: string): void;
  (e: "update:passwordConfirm", passwordConfirm: string): void;
  (e: "update:profilePictureFile", pictureFile: File | null): void;
}

const emit = defineEmits<EventEmitter>();

const onSelectFile = ($event) => {
  const target = $event.target as HTMLInputElement;
  if (target && target.files) {
    emit("update:profilePictureFile", target.files[0]);
  }
};

const clearInput = (inputEvent: Event) => {
  return (inputEvent.target as HTMLInputElement)?.value || "";
};
</script>

<style scoped lang="scss"></style>
