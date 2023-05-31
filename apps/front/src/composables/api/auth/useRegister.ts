import { Me } from './useMe';
import {API_URL, POST} from "~/constants/http";

export default async function useRegister(email: string, password: string): Promise<Me> {
  const { $appFetch } = useNuxtApp();
  const response = await $appFetch<Me>(API_URL + '/user', {
    method: POST,
    body: {
      email,
      password,
    },
  });
  if (!response) {
    throw createError('Error while registering user');
  }
  return response;
}
