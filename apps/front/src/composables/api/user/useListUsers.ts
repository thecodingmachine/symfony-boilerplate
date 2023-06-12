import { User } from "~/utils/types";
import {API_URL, GET} from "~/constants/http";


export default async function useListUsers(): Promise<Array<User>> {
  const { $appFetch } = useNuxtApp();
  const response = await $appFetch<Array<User>>(API_URL + '/users', {
    method: GET,
  });
  if (!response) {
    throw createError('Error while retrieving users list');
  }
  return response;
}
