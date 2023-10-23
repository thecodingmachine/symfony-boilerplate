import { POST, PUT } from "~/constants/http";
import { User, UserId } from "~/types/User";
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
      password: string | undefined = undefined,
      profilePictureFile: File | null = null
    ) {
      try {
        resetError();
        const formData = new FormData();
        formData.append('email', user.email)
        if (password){
          formData.append('password', password)
        }
        if (profilePictureFile){
          formData.append('profilePictureFile', profilePictureFile)
        }
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
