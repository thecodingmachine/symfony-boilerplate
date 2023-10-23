<template>
  <form @submit.prevent="$emit('submit-company', { ...form, identityFile })">
    <div>
      <label for="email">Name</label>
      <input v-model="form.name" type="text" />
    </div>
    <div>
      <label for="profilePicture">Identity file</label>
      <input type="file" accept="image/*,.pdf" @input="onSelectFile" />
    </div>
    <button type="submit">Submit</button>
  </form>
</template>

<script setup lang="ts">
import { PropType } from "vue";
import { Company } from "~/types/Company";

const props = defineProps({
  company: {
    type: Object as PropType<Company>,
    required: true,
  },
  errorMessage: String,
});

const form = ref<Company>(props.company);

let identityFile = ref<File>(null);

const onSelectFile = ($event) => {
  const target = $event.target as HTMLInputElement;
  if (target && target.files) {
    identityFile = target.files[0];
  }
};
</script>

<style scoped lang="scss"></style>
