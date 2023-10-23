<template>
  <span class="p-float-label">
    <input
      :id="id"
      ref="fileInput"
      type="file"
      class="hidden"
      :name="name"
      :multiple="multiple"
      :accept="accept"
      @change="selectFile"
    />
    <span class="p-buttonset">
      <Button
        :label="buttonLabel"
        :icon="!hasFile ? iconEmptyClass : ''"
        :class="buttonClass"
        @mouseup.prevent="onUploadButtonClick()"
      />
      <Button
        icon="pi pi-times"
        aria-label="Unselect file"
        :disabled="!hasFile"
        @click.prevent="clearFiles"
      />
    </span>
  </span>
</template>
<script lang="ts" setup>
defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  defaultButtonText: {
    type: String,
    default: "Choose File",
  },
  manyFileSelectedButtonText: {
    type: String,
    default: "Files selected",
  },
  fileSelectedButtonText: {
    type: String,
    default: null,
  },
  buttonClass: {
    type: String,
    default: "",
  },
  iconEmptyClass: {
    type: String,
    default: "pi pi-upload",
  },
  multiple: {
    type: Boolean,
    default: false,
  },
  name: String,
  id: String,
  accept: String,
});

const fileInput = ref<HTMLInputElement>();
const files = ref<FileList | null>();
const hasFile = ref(false);

const emits = defineEmits<{
  selectFile: [file: FileList];
}>();

const onUploadButtonClick = () => {
  if (fileInput && fileInput.value) {
    fileInput.value.click();
  }
};

const buttonLabel = computed(() => {
  if (!hasFile.value) {
    return props.defaultButtonText;
  } else {
    const fileCount = (files.value as FileList).length;
    if (fileCount === 1) {
      return props.fileSelectedButtonText ?? (files.value as FileList)[0].name;
    } else {
      return fileCount + props.manyFileSelectedButtonText;
    }
  }
});

const selectFile = ($event: Event) => {
  const target = $event.target as HTMLInputElement;
  if (target && target.files) {
    hasFile.value = true;
    files.value = target.files;
    emits("selectFile", target.files);
  }
};

const clearFiles = () => {
  hasFile.value = false;
  files.value = null;
  if (fileInput && fileInput.value) {
    fileInput.value.value = "";
  }
};
</script>
