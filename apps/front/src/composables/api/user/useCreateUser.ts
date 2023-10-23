import { POST } from "~/constants/http";
import type { User } from "~/types/User";
import type { UserInput } from "~/types/UserInput";
import type { Ref } from "vue";
import useBasicError from "~/composables/useBasicError";

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
        const formData = new FormData();
        formData.append("email", user.email);
        formData.append("password", user.password);
        formData.append("profilePictureFile", user.profilePicture);
        const response = await $appFetch<User>("/users", {
          method: POST,
          body: formData,
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
