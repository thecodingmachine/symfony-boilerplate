import { GET } from "~/constants/http";
import {Company} from "~/types/Company";
import useAppFetch from "~/composables/useAppFetch";

export default async function useGetCompany(companyId: string) {
  return useAppFetch<Company>(() => "/companies/" + companyId, {
    key: "getCompany",
    method: GET,
  });
}
