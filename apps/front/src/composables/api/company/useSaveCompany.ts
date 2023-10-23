import useBasicError from "~/composables/useBasicError";
import { Company } from "~/types/Company";
import { POST } from "~/constants/http";

export default function useSaveCompany() {
  const { $appFetch } = useNuxtApp();
  const { setError, resetError, errorMessage } = useBasicError();
  return {
    errorMessage,
    async saveCompany(company: Company) {
      try {
        resetError();
        const formData = new FormData();
        formData.append("name", company.name);
        formData.append("identityFile", company.identityFile);
        const url = "/companies" + (company.id ? "/" + company.id : "");
        const response = await $appFetch<Company>(url, {
          method: POST,
          body: formData,
        });
        return response;
      } catch (e) {
        setError(e);
        throw e;
      }
    },
  };
}
