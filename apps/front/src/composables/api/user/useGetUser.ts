import {API_URL, GET} from "~/constants/http";
import {User} from "~/utils/types";


export default async function useGetUser(userId: string): Promise<User> {
  const { $appFetch } = useNuxtApp();
  const response = await $appFetch<User>(API_URL + '/users/' + userId, {
    method: GET,
  });
  if (!response) {
    throw createError('Error while retrieving user');
  }
  return response;
}
