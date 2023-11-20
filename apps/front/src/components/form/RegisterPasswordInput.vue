<template>
  <span class="p-float-label">
    <Password
      toggle-mask
      :input-id="inputId"
      medium-regex="^(?=(?:[^A-Z]*[A-Z]){2,})(?=(?:[^a-z]*[a-z]){2,})(?=(?:[^\d]*\d){2,})(?=(?:[^\W_]*[\W_]){1,}).{8,}$"
      strong-regex="^(?=(?:[^A-Z]*[A-Z]){2,})(?=(?:[^a-z]*[a-z]){2,})(?=(?:[^\d]*\d){2,})(?=(?:[^\W_]*[\W_]){2,}).{8,}$"
      v-bind="$attrs"
    >
      <template #header>
        <small>
          <p class="mt-2">
            {{ $t("components.form.registerPasswordInput.suggestions") }}
          </p>
          <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
            <li>
              {{
                $t("components.form.registerPasswordInput.suggestionsLowercase")
              }}
            </li>
            <li>
              {{
                $t("components.form.registerPasswordInput.suggestionsUppercase")
              }}
            </li>
            <li>
              {{
                $t("components.form.registerPasswordInput.suggestionsNumber")
              }}
            </li>
            <li>
              {{
                $t("components.form.registerPasswordInput.suggestionsSymbol")
              }}
            </li>
            <li>
              {{ $t("components.form.registerPasswordInput.suggestionsSize") }}
            </li>
            <li>
              {{
                $t("components.form.registerPasswordInput.suggestionsUnicity")
              }}
            </li>
          </ul>
        </small>
        <Divider />
      </template>
    </Password>
    <label :for="inputId">
      <slot>
        {{ $t("components.form.registerPasswordInput.password") }}
      </slot>
    </label>
  </span>
</template>
<script lang="ts" setup>
defineOptions({
  inheritAttrs: false,
});
defineProps<{
  inputId: string;
}>();
</script>
