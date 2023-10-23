<template>
  <div class="grid">
    <div class="col-8">
      <form @submit.prevent.stop="submit">
        <div class="field col-12">
          <span class="p-float-label">
            <InputText
              id="user-email"
              v-model="state.email"
              type="text"
              :placeholder="$t('components.user.form.email')"
            />
            <label for="user-email">{{
              $t("components.user.form.email")
            }}</label>
          </span>
        </div>
        <div class="field col-12">
          <FormRegisterPasswordInput
            v-model="password"
            input-id="user-password"
          />
        </div>
        <div class="field col-12">
          <FormRegisterPasswordInput
            v-model="passwordConfirm"
            input-id="user-passwordConfirm"
          >
            {{ $t("components.user.form.passwordConfirm") }}
          </FormRegisterPasswordInput>
        </div>
        <div v-show="!isPasswordConfirmed">
          {{ $t("components.user.form.errorPasswordConfirm") }}
        </div>
        <div class="field col-12">
          <FormSimpleFileUpload
            button-text="Choose File"
            :multiple="true"
            accept="image/*"
            @select-file="selectProfilePicture"
          />
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
    <div class="col-4">
      <div
        v-if="state.profilePictureUrl !== null"
        class="relative flex justify-content-center"
      >
        <Image
          :src="publicFileURL(state.profilePictureUrl)"
          width="250"
          class="surface-border border-1"
        />
        <div
          class="absolute top-0 bottom-0 left-0 right-0 flex align-content-center appear-on-hover"
        >
          <ConfirmPopup></ConfirmPopup>
          <Button
            class="m-auto cursor-pointer py-1 px-2 font-bold"
            severity="danger"
            style="color: white"
            aria-label="delete"
            @click="deleteProfilePicture"
            >x</Button
          >
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import type { User } from "~/types/User";
import { publicFileURL } from "~/composables/storage/storage";
import { useConfirm } from "primevue/useconfirm";
import type { UserInput } from "~/types/UserInput";

interface Props {
  defaultValue: UserInput;
}
const props = withDefaults(defineProps<Props>(), {
  defaultValue() {
    return {
      email: "",
      profilePicture: null,
      profilePictureUrl: null,
    };
  },
});
const confirm = useConfirm();
const { t } = useI18n();

const state = reactive({ ...props.defaultValue });
const password = ref("");
const passwordConfirm = ref("");
const profilePicture = ref<File>();

const isPasswordConfirmed = computed(
  () => password.value === passwordConfirm.value
);

const isPasswordEmpty = computed(() => !password.value);
const securedPassword = computed(() =>
  isPasswordConfirmed && isPasswordEmpty ? password.value : ""
);
const isValid = isPasswordConfirmed;

interface EventEmitter {
  (e: "submit", value: UserInput): void;
  (e: "cancel"): void;
  (e: "deleteUserPicture"): void;
}

const emits = defineEmits<EventEmitter>();

const selectProfilePicture = (files: FileList) => {
  profilePicture.value = files[0];
  console.log(profilePicture.value);
};
const submit = () => {
  emits("submit", {
    ...state,
    password: securedPassword.value,
    profilePicture: profilePicture.value ?? null,
  });
};

const deleteProfilePicture = (event: Event) => {
  confirm.require({
    target: event.currentTarget as HTMLElement,
    message: t("components.user.form.confirmDeleteUserPicture"),
    icon: "pi pi-info-circle",
    acceptClass: "p-button-danger p-button-sm",
    acceptLabel: t("misc.confirmPopup.accept"),
    rejectLabel: t("misc.confirmPopup.reject"),
    accept: () => {
      emits("deleteUserPicture");
    },
  });
};

const cancel = () => {
  emits("cancel");
};
</script>

<style scoped lang="scss"></style>
