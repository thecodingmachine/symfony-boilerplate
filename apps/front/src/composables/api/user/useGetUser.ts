import { GET } from "~/constants/http";
import type { User } from "~/types/User";
import useAppFetch from "~/composables/useAppFetch";

export default async function useGetUser(userId: string) {
  return useAppFetch<User>(() => "/users/" + userId, {
    key: "getUser" + userId,
    method: GET,
  });
}
