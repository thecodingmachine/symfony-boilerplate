import type { User } from "~/types/User";
import { DELETE } from "~/constants/http";
import useBasicError from "~/composables/useBasicError";

export default function useDeleteUser() {
  const { $appFetch } = useNuxtApp();

  const { setError, resetError, errorMessage } = useBasicError();
  return {
    errorMessage,
    deleteUser: async (user: User) => {
      try {
        resetError();
        const response = await $appFetch("/users/" + user.id, {
          method: DELETE,
        });
        if (!response) {
          throw createError("Error while deleting user");
        }
        return response;
      } catch (e: any) {
        await setError(e);
        throw e;
      }
    },
  };
}
