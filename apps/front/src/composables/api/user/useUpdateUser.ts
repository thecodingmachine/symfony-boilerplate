import { POST } from "~/constants/http";
import type { User, UserId } from "~/types/User";
import type { UserInput } from "~/types/UserInput";
import useBasicError from "~/composables/useBasicError";

export default function useUpdateUser() {
  const { $appFetch } = useNuxtApp();
  const { setError, resetError, errorMessage } = useBasicError();
  return {
    errorMessage,
    async updateUser(userId: UserId, user: UserInput) {
      try {
        resetError();
        const formData = new FormData();
        formData.append("email", user.email);
        formData.append("password", user.password);
        formData.append("profilePictureFile", user.profilePicture);
        const response = await $appFetch<User>("/users/" + userId, {
          method: POST,
          body: formData,
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
