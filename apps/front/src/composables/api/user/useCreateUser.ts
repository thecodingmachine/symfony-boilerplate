import { POST } from "~/constants/http";
import { User } from "~/types/User";
import { Ref } from "vue";
import useBasicError from "~/composables/useBasicError";

export default function useCreateUser(): {
  errorMessage: Readonly<Ref<string>>;
  createUser(email: string, password: string): Promise<User | null>;
} {
  const { $appFetch } = useNuxtApp();

  const { setError, resetError, errorMessage } = useBasicError();

  return {
    errorMessage,
    async createUser(email: string, password: string): Promise<User | null> {
      try {
        resetError();
        const response = await $appFetch<User>("/users", {
          method: POST,
          body: {
            email,
            password,
          },
        });
        if (!response) {
          throw createError("Error while registering user");
        }
        return response;
      } catch (e: any) {
        setError(e);
        throw e;
      }
    },
  };
}
