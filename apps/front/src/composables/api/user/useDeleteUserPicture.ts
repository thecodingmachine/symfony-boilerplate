import { DELETE } from "~/constants/http";
import type { User, UserId } from "~/types/User";
import useBasicError from "~/composables/useBasicError";

export default function useDeleteUserPicture() {
  const { $appFetch } = useNuxtApp();
  const { setError, resetError, errorMessage } = useBasicError();
  return {
    errorMessage,
    deleteUserPicture: async (userId: UserId) => {
      try {
        resetError();
        const response = await $appFetch("/users/" + userId + "/picture", {
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
