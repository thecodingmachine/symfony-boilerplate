<template>
  <div class="field">
    <span class="p-float-label">
      <slot :updated="updated" :is-active-violation="displayError" />
    </span>
    <small v-if="displayError" class="p-error">{{ $t(violation) }}</small>
  </div>
</template>
<script setup lang="ts">
interface Props {
  violation: string;
}
const props = withDefaults(defineProps<Props>(), {
  violation() {
    return "";
  },
});
const hasBeenUpdated = ref(false);
const updated = () => {
  hasBeenUpdated.value = true;
};
const displayError = computed(() => {
  if (hasBeenUpdated.value) {
    return false;
  }
  return !!props.violation;
});
watchEffect(() => {
  if (props.violation) {
    hasBeenUpdated.value = false;
  }
});
</script>
<script></script>
