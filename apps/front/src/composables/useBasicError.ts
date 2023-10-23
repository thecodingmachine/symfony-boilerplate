import type { Ref } from "vue";
import type { BasicError } from "~/types/BasicError";

export default function useBasicError() {
  const error: Ref<BasicError | null> = ref(null);
  const errorMessage = computed(() => {
    // Dont display message on 500 (handled via toasters)
    if (
      error.value?.status &&
      (error.value.status > 500 || error.value.status === 403)
    ) {
      return "";
    }
    if (error.value?.detail) {
      return error.value?.detail;
    }
    if (error.value?.message) {
      return Array.isArray(error.value.message)
        ? error.value.message.join(".")
        : error.value.message;
    }

    if (error.value?.title) {
      return error.value?.title;
    }
    return "";
  });
  const setError = async (e: any) => {
    error.value = e;
    if (e.response && e.response._data) {
      error.value = await e.response._data;
    }
  };
  return {
    setError,
    resetError: () => {
      error.value = null;
    },
    error: readonly(error),
    errorMessage,
  };
}
