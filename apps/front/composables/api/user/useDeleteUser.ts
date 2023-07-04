import { User } from "~/types/user";
import {API_URL, DELETE} from "~/constants/http";


export default async function useDeleteUser(user: User) {
  const { $appFetch } = useNuxtApp();
  const response = await $appFetch('/users/' + user.id, {
    method: DELETE,
  });
  if (!response) {
    throw createError('Error while deleting user');
  }
  return response;
}
