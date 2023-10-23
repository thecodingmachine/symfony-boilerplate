import { User } from "~/types/user";
import { DELETE } from "~/constants/http";

export default function useDeleteCompany() {
  const { $appFetch } = useNuxtApp();
  return {
    deleteCompany: async (company: Company) => {
      const response = await $appFetch("/companies/" + company.id, {
        method: DELETE,
      });
      if (!response) {
        throw createError("Error while deleting company");
      }
      return response;
    },
  };
}
