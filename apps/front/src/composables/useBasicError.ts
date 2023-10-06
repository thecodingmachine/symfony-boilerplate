import { Ref } from "vue";
import { BasicError } from "~/types/BasicError";

export default function useBasicError() {
  const error: Ref<BasicError | null> = ref(null);
  const errorMessage = computed(() => {
    return (
      error.value?.detail || error.value?.message || error.value?.error || ""
    );
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
