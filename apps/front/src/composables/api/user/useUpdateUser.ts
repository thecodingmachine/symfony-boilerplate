import {API_URL, PUT} from "~/constants/http";


export default async function useUpdateUser(userId: string, params: {}) {
  const { $appFetch } = useNuxtApp();
  const response = await $appFetch(API_URL + '/user/' + userId, {
    method: PUT,
    body: params
  });
  if (!response) {
    throw createError('Error while updating user');
  }
  return response;
}
