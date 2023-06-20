import { defineStore } from 'pinia';
import useMe, { Me } from '~/composables/api/auth/useMe';
import useLogin from '~/composables/api/auth/useLogin';
import { HTTP_UNAUTHORIZED } from '~/constants/http';
import {User} from "~/types/user";

type AuthState = {
  authUser: Me | null;
  isPending: boolean;
  authUrl: string;
};

export const useAuthUser = defineStore({
  id: 'auth-store',
  state: (): AuthState => ({
    authUser: null,
    isPending: false,
    authUrl: '',
  }),
  actions: {
    async authenticateUser(username: string, password: string) {
      const authenticate = useLogin();
      try {
        this.startPending();
        const me = await authenticate(username, password);
        this.setAuthUser(me);
        this.endPending();
        return me;
      } catch (e) {
        this.endPending();
        throw e;
      }
    },
    removeAuthUser() {
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
        return { data: null, error: null, isPending: this.isPending };
      }
      // Our session is based on the PHPSESSID cookie
      const me = useMe();
      try {
        this.startPending();
        const authUser = await me();
        this.setAuthUser(authUser);
        this.endPending();
        return { data: authUser, error: null, isPending: this.isPending };
      } catch (exception: any) {
        this.endPending();
        const is401 = exception?.response?.status === HTTP_UNAUTHORIZED;
        if (!is401) {
          // TODO error store in appFetch
          throw exception;
        }
        // eslint-disable-next-line no-underscore-dangle
        const ret = await exception.response._data;
        this.authUrl = ret?.url || '';
        return {
          data: null,
          error: ret,
          isPending: this.isPending,
        };
      }
    },
  },
  getters: {
    isAuthenticated: (state) => !!state.authUser,
    isAuthUser: (state) => (user: User) => state.authUser?.id === user.id,
  },
});

export default useAuthUser;
