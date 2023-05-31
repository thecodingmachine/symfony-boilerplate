import {API_URL} from "~/constants/http";

export interface Me {
  username: string;
  email: string;
}

export default function useMe(): () => Promise<Me> {
  const { $appFetch } = useNuxtApp();
  return async () => {
    const res = await $appFetch<Me>(API_URL + '/auth/me');
    if (!res) {
      throw createError('/api/1.0/auth/me has an empty body');
    }
    return res;
  };
}
