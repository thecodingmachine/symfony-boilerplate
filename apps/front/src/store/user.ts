import { defineStore } from 'pinia';
import { User } from "~/utils/types";
import useListUsers from "~/composables/api/user/useListUsers";
import useDeleteUser from "~/composables/api/user/useDeleteUser";

export const useUser = defineStore({
  id: 'user-store',
  state: () => ({
    users: [] as Array<User>,
    isPending: false,
  }),
  actions: {
    async listUsers() {
      this.users = await useListUsers();
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
