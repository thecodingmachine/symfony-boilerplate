<template>
  <div class="grid">
    <div class="col-12">
      <form @submit.prevent.stop="submit">
        <div class="field col-12">
          <span class="p-float-label">
            <InputText
              id="user-email"
              v-model="state.email"
              type="text"
              :placeholder="$t('components.user.form.email')"
              :class="{ 'p-invalid': violations.email }"
            />
            <label for="user-email">{{
              $t("components.user.form.email")
            }}</label>
          </span>
          <small class="p-error">{{ violations.email }}</small>
        </div>
        <div class="field col-12">
          <FormRegisterPasswordInput
            v-model="password"
            input-id="user-password"
            :class="{
              'p-invalid': violations.password || !isPasswordConfirmed,
            }"
          />
          <small class="p-error">
            {{ violations.password }}
          </small>
        </div>
        <div class="field col-12">
          <FormRegisterPasswordInput
            v-model="passwordConfirm"
            input-id="user-passwordConfirm"
            :class="{ 'p-invalid': !isPasswordConfirmed }"
          >
            {{ $t("components.user.form.passwordConfirm") }}
          </FormRegisterPasswordInput>
          <small v-show="!isPasswordConfirmed" class="p-error">
            {{ $t("components.user.form.errorPasswordConfirm") }}
          </small>
        </div>
        <div>
          <slot name="buttons" :is-valid="isValid" :cancel="cancel">
            <Button
              type="button"
              severity="danger"
              class="mr-2 mb-2"
              @click="cancel"
            >
              {{ $t("components.user.form.buttonCancel") }}
            </Button>
            <Button type="submit" :disabled="!isValid" class="mr-2 mb-2">
              {{ $t("components.user.form.ok") }}
            </Button>
          </slot>
        </div>
      </form>
    </div>
  </div>
</template>
<script setup lang="ts">
import type { User } from "~/types/User";

interface State extends Omit<User, "id"> {}
interface Props {
  defaultValue?: State;
  violations?: {
    [K in keyof State]?: string;
  } & {
    password?: string;
  };
}
const props = withDefaults(defineProps<Props>(), {
  defaultValue() {
    return {
      email: "",
    };
  },
  violations() {
    return {};
  },
});
const state = reactive({ ...props.defaultValue });
const password = ref("");
const passwordConfirm = ref("");

const isPasswordConfirmed = computed(
  () => password.value === passwordConfirm.value,
);

const isPasswordEmpty = computed(() => !password.value);
const securedPassword = computed(() =>
  isPasswordConfirmed && isPasswordEmpty ? password.value : "",
);
const isValid = isPasswordConfirmed;

interface EventEmitter {
  (
    e: "submit",
    value: Omit<User, "id"> & {
      password: string;
    },
  ): void;
  (e: "cancel"): void;
}

const emits = defineEmits<EventEmitter>();
const submit = () => {
  emits("submit", { ...state, password: securedPassword.value });
};
const cancel = () => {
  emits("cancel");
};
</script>

<style scoped lang="scss"></style>
