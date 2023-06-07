import { defineStore } from 'pinia';
import { User } from "~/utils/types";
import useListUsers from "~/composables/api/user/useListUsers";
import useDeleteUser from "~/composables/api/user/useDeleteUser";
import useGetUser from "~/composables/api/user/useGetUser";
import useUpdateUser from "~/composables/api/user/useUpdateUser";

export const useUser = defineStore({
  id: 'user-store',
  state: () => ({
    user: null as User|null,
    users: [] as Array<User>,
    isPending: false,
  }),
  actions: {
    async listUsers() {
      this.users = await useListUsers();
    },
    async getUser(userId: string) {
      this.user = await useGetUser(userId);
    },
    async updateUser(userId: string, params: {}) {
      this.user = await useUpdateUser(userId, params);
    },
    async deleteUser(user: User) {
      await useDeleteUser(user);
      this.users = this.users.filter(userItem => userItem.id !== user.id);
    },
  },
  getters: {
  },
});

export default useUser;
