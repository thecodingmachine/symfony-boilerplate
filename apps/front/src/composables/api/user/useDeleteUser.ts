import { User } from "~/types/User";
import { DELETE } from "~/constants/http";

export default function useDeleteUser() {
  const { $appFetch } = useNuxtApp();
  return {
    deleteUser: async (user: User) => {
      const response = await $appFetch("/users/" + user.id, {
        method: DELETE,
      });
      if (!response) {
        throw createError("Error while deleting user");
      }
      return response;
    },
  };
}
