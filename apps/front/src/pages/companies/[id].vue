<template>
  <div v-if="!companyPending">
    <h1>Edit company {{ company.name }}</h1>
    <CompanyForm
      :company="company"
      :error-message="errorMessage"
      @submitCompany="updateCompany"
    />
  </div>
</template>

<script setup lang="ts">
import { Company } from "~/types/Company";
import useGetCompany from "~/composables/api/company/useGetCompany";
import useSaveCompany from "~/composables/api/company/useSaveCompany";

const route = useRoute();

const { data: company, pending: companyPending } = await useGetCompany(
  route.params.id
);
// Clone for not have same object as child

const { saveCompany, errorMessage } = useSaveCompany();
const updateCompany = async (submittedCompany: Company) => {
  await saveCompany(submittedCompany);
  await navigateTo("/companies");
};
</script>

<style scoped lang="scss"></style>
