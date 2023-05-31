import { Me } from "./useMe";
import { POST } from "~/constants/http";
import useAppFetch from "~/composables/useAppFetch";

export function useSamlLogin(): (
  email: string,
  password: string | undefined
) => Promise<Me> {
  const { $appFetch } = useNuxtApp();
  return async (email: string, password: string | undefined) => {
    const response = await $appFetch<Me>("/auth/sso/saml2/login", {
      method: POST,
      body: {
        username: email,
        password,
      },
    });
    if (!response) {
      throw createError(
        "/api/1.0/auth/sso/saml2/login has an empty body, the profile (me) should be returned"
      );
    }
    return response;
  };
}

export async function useLogin(username: string, password: string | undefined) {
  return useAppFetch(() => "/login", {
    key: "login",
    method: POST,
    body: {
      username,
      password,
    },
  });
}
