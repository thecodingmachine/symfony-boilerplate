import {API_URL, GET} from "~/constants/http";


export default async function useGetUser(userId: string) {
  const { $appFetch } = useNuxtApp();
  const response = await $appFetch(API_URL + '/user/' + userId, {
    method: GET,
  });
  if (!response) {
    throw createError('Error while retrieving user');
  }
  return response;
}
