import { Me } from './useMe';

export default function useLogin(): (email: string, password: string | undefined) => Promise<Me> {
  const { $appFetch } = useNuxtApp();
  return async (email: string, password: string | undefined) => {
    const response = await $appFetch<Me>('/api/1.0/auth/sso/saml2/login', {
      method: 'post',
      body: {
        username: email,
        password,
      },
    });
    if (!response) {
      throw createError('/api/1.0/auth/sso/saml2/login has an empty body, the profile (me) should be returned');
    }
    return response;
  };
}
