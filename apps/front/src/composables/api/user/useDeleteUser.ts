import { User } from "~/utils/types";
import {API_URL, DELETE} from "~/constants/http";


export default async function useDeleteUser(user: User) {
  const { $appFetch } = useNuxtApp();
  const response = await $appFetch(API_URL + '/users/' + user.id, {
    method: DELETE,
  });
  if (!response) {
    throw createError('Error while deleting user');
  }
  return response;
}
