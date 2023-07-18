import { User } from "~/types/user";
import { GET } from "~/constants/http";
import useAppFetch from "~/composables/useAppFetch";

export default async function useListUsers() {
  return useAppFetch<Array<User>>(() => "/users", {
    key: "listUsers",
    method: GET,
  });
}
