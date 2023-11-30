import { PUT } from "~/constants/http";
import type { User, UserId } from "~/types/User";
import useBasicError from "~/composables/useBasicError";

type UserInput = Omit<User, "id"> & {
  password: string;
};
export default function useUpdateUser() {
  const { $appFetch } = useNuxtApp();
  const { setError, resetError, errorMessage, violations } = useBasicError();
  return {
    errorMessage,
    violations,
    async updateUser(userId: UserId, user: UserInput) {
      try {
        resetError();
        const response = await $appFetch<User>("/users/" + userId, {
          method: PUT,
          body: user,
        });
        if (!response) {
          throw createError("Error while updating user");
        }
        return response;
      } catch (e: any) {
        setError(e);
        throw e;
      }
    },
  };
}
