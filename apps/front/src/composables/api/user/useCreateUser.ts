import { POST } from "~/constants/http";
import type { User } from "~/types/User";
import type { Ref } from "vue";
import useBasicError from "~/composables/useBasicError";

type UserInput = Omit<User, "id"> & {
  password: string;
};
export default function useCreateUser(): {
  errorMessage: Readonly<Ref<string>>;
  createUser(user: UserInput): Promise<User | null>;
} {
  const { $appFetch } = useNuxtApp();

  const { setError, resetError, errorMessage } = useBasicError();

  return {
    errorMessage,
    async createUser(user: UserInput): Promise<User | null> {
      try {
        resetError();
        const response = await $appFetch<User>("/users", {
          method: POST,
          body: user,
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
