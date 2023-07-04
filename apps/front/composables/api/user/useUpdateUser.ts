import {API_URL, PUT} from "~/constants/http";
import {User} from "~/types/user";


export default async function useUpdateUser(userId: string, user: User): Promise<User> {
  const { $appFetch } = useNuxtApp();
  const response = await $appFetch<User>('/users/' + userId, {
    method: PUT,
    body: user,
  });
  if (!response) {
    throw createError('Error while updating user');
  }
  return response;
}
