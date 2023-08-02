import { defineStore } from "pinia";
import useMe, { Me } from "~/composables/api/auth/useMe";
import { HTTP_UNAUTHORIZED, POST } from "~/constants/http";
import { User } from "~/types/user";
import { AppFetch } from "~/types/AppFetch";

type AuthState = {
  authUser: Me | null;
  isPending: boolean;
  authUrl: string;
};

const login = async (
  fetcher: AppFetch<any>,
  username: string,
  password: string
) => {
  return fetcher("/login", {
    method: POST,
    body: {
      username,
      password,
    },
  }) as Promise<User>;
};

export const useAuthUser = defineStore({
  id: "auth-store",
  state: (): AuthState => ({
    authUser: null,
    isPending: false,
    authUrl: "",
    //authUrl: ""
  }),
  actions: {
    async authenticateUser(
      username: string,
      password: string,
      fetch: AppFetch<any>
    ) {
      return login(fetch, username, password);
    },
    resetAuth() {
      this.authUser = null;
    },
    setAuthUser(authUser: Me) {
      this.authUser = authUser;
    },
    startPending() {
      this.isPending = true;
    },
    endPending() {
      this.isPending = false;
    },
    async syncMe() {
      if (this.isPending) {
        return;
      }
      this.startPending();
      // Our session is based on the PHPSESSID cookie
      const me = useMe();
      try {
        const authUser = await me();
        this.setAuthUser(authUser);
        this.endPending();
        return;
      } catch (exception: any) {
        this.endPending();
        const is401 = exception?.response?.status === HTTP_UNAUTHORIZED;
        if (!is401) {
          // TODO error store in appFetch
          throw exception;
        }
        const ret = await exception.response._data;
        this.authUrl = ret?.url || "/auth/login";
        return;
      }
    },
  },
  getters: {
    isAuthenticated: (state) => !!state.authUser,
    isAuthUser: (state) => (user: User) => state.authUser?.id === user.id,
  },
});

export default useAuthUser;
