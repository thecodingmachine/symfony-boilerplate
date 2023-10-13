import { defineStore } from "pinia";
import { HTTP_UNAUTHORIZED, POST } from "~/constants/http";
import { User } from "~/types/User";
import { AppFetch } from "~/types/AppFetch";
import useBasicError from "~/composables/useBasicError";

const login = <T>(fetcher: AppFetch<T>, username: string, password: string) => {
  return fetcher("/login", {
    method: POST,
    body: {
      username,
      password,
    },
  });
};

const logout = (fetcher: AppFetch<any>) => {
  return fetcher("/auth/logout", {
    method: POST,
  }) as Promise<null>;
};

export const useAuthUser = defineStore("auth-store", () => {
  const me = ref();
  const { error, resetError, setError } = useBasicError();
  const isMePending = ref(false);
  const authUrl = ref("/auth/login");
  const refresh = async ($appFetch: AppFetch<User | undefined>) => {
    resetError();
    isMePending.value = true;
    try {
      const res = await $appFetch("auth/me");
      me.value = res;
    } catch (exception: any) {
      isMePending.value = false;
      const is401 = exception?.response?.status === HTTP_UNAUTHORIZED;
      if (!is401) {
        return setError(exception);
      }
      const ret = await exception.response._data;
      authUrl.value = ret?.url || "/auth/login";
    }
    isMePending.value = false;
  };

  const resetAuth = () => (me.value = null);
  const logoutUser = async (fetch: AppFetch<any>) => {
    await logout(fetch);
    resetAuth();
  };

  return {
    me,
    meError: error,
    isMePending,
    authUrl,

    isAuthenticated: computed(() => !!me.value),

    isAuthUser: (user: User) => me.value?.id === user.id,
    resetAuth,
    refresh,
    logoutUser,
    async authenticateUser(
      username: string,
      password: string,
      fetch: AppFetch<any>
    ) {
      const user = await login<User>(fetch, username, password);
      me.value = user;
      return user;
    },
  };
});
export default useAuthUser;
