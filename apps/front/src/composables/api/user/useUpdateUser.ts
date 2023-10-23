import { PUT } from "~/constants/http";
import type { User, UserId } from "~/types/User";
import useBasicError from "~/composables/useBasicError";

type UserInput = Omit<User, "id">;
export default function useUpdateUser() {
  const { $appFetch } = useNuxtApp();
  const { setError, resetError, errorMessage } = useBasicError();
  return {
    errorMessage,
    async updateUser(
      userId: UserId,
      user: UserInput,
      password: string | undefined = undefined
    ) {
      try {
        resetError();
        const body = password
          ? {
              ...user,
              password,
            }
          : user;
        const response = await $appFetch<User>("/users/" + userId, {
          method: PUT,
          body,
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
