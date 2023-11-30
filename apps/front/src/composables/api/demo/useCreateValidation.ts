import { POST } from "~/constants/http";
import useBasicError from "~/composables/useBasicError";

export default function useCreateValidation() {
  const { $appFetch } = useNuxtApp();

  const { setError, resetError, errorMessage, error, violations } =
    useBasicError();

  return {
    errorMessage,
    error,
    violations,
    async post<T extends Record<string, any>>(state: T) {
      try {
        resetError();
        const response = await $appFetch<T>("/demo/validation", {
          method: POST,
          body: state,
        });
        if (!response) {
          throw createError("Error while registering demo/validation");
        }
        return response;
      } catch (e: any) {
        setError(e);
        throw e;
      }
    },
  };
}
