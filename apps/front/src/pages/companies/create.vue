<template>
  <div>
    <h1>Create new Company</h1>
    <CompanyForm
      :company="company"
      :error-message="errorMessage"
      @submitCompany="createCompany"
    />
  </div>
</template>

<script setup lang="ts">
import { Company } from "~/types/Company";
import useSaveCompany from "~/composables/api/company/useSaveCompany";

const company = ref<Company>({});

const { saveCompany, errorMessage } = useSaveCompany();
const createCompany = async (submittedCompany: Company) => {
  await saveCompany(submittedCompany);
  await navigateTo("/companies");
};
</script>

<style scoped lang="scss"></style>
