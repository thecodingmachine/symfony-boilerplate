<template>
  <div>
    <h1>Companies</h1>
    <NuxtLink :to="`/companies/create`"
    >
      <button>Create new</button>
    </NuxtLink>
    <div v-show="companiesPending">(En cours de chargement)</div>
    <div v-show="error">{{ error }}}</div>
    <table v-if="companies.length > 0">
      <thead>
        <tr>
          <th>Name</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="company in companies" :key="company.id">
          <td>{{ company.name }}</td>
          <td>
            <NuxtLink :to="`/companies/${company.id}`"
            >
              <button>Update</button>
            </NuxtLink>
          </td>
          <td>
            <button
              @click="onDeleteCompany(company)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-if="companies.length === 0">
      No company
    </div>
  </div>
</template>

<script setup lang="ts">
import { Company } from "~/types/Company";
import useListCompanies from "~/composables/api/company/useListCompanies";
import useDeleteCompany from "~/composables/api/company/useDeleteCompany";

const { deleteCompany } = useDeleteCompany();

const {
  data: companies,
  error,
  pending: companiesPending,
  refresh: companiesRefresh,
} = await useListCompanies();

const onDeleteCompany = async (company: Company) => {
  try {
    await deleteCompany(company);
    await companiesRefresh();
  } catch (e) {
    logger.error(e);
  }
};
</script>

<style scoped lang="scss"></style>
