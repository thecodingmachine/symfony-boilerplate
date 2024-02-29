<template>
  <Card class="surface-section">
    <template #title
      ><h1 class="font-medium text-3xl text-900">Validations</h1>
      <Divider
    /></template>
    <template #content>
      <DemoValidationForm
        class="card"
        :violations="violations"
        @submit="submit"
        @cancel="cancel"
      />
    </template>
  </Card>
</template>
<script lang="ts" setup>
import useCreateValidation from "~/composables/api/demo/useCreateValidation";

const { violations, post } = useCreateValidation();
const submit = async (state: any) => {
  await post(state);
};
const cancel = () => {};
</script>
;
