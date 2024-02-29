<template>
  <div>
    <form @submit.prevent.stop="submit">
      <div class="grid p-fluid">
        <ViolationField class="col-6" :violation="violations.textField">
          <template #default="{ updated, isActiveViolation }">
            <InputText
              v-model="state.textField"
              type="text"
              :placeholder="$t('components.demo.validation.form.textField')"
              :class="{
                'p-invalid': isActiveViolation,
              }"
              @update:model-value="updated"
            />
            <label>{{ $t("components.demo.validation.form.textField") }}</label>
          </template>
        </ViolationField>
        <ViolationField class="col-6" :violation="violations.siret">
          <template #default="{ updated, isActiveViolation }">
            <InputText
              v-model="state.siret"
              type="text"
              :placeholder="$t('components.demo.validation.form.siret')"
              :class="{
                'p-invalid': isActiveViolation,
              }"
              @update:model-value="updated"
            />
            <label>{{ $t("components.demo.validation.form.siret") }}</label>
          </template>
        </ViolationField>

        <ViolationField class="col-6" :violation="violations.startDate">
          <template #default="{ updated, isActiveViolation }">
            <Calendar
              v-model="state.startDate"
              :placeholder="$t('components.demo.validation.form.startDate')"
              :class="{
                'p-invalid': isActiveViolation,
              }"
              @update:model-value="updated"
            />
            <label>{{ $t("components.demo.validation.form.startDate") }}</label>
          </template>
        </ViolationField>
        <ViolationField
          class="col-6"
          :violation="violations.nestedDemoEntity?.name"
        >
          <template #default="{ updated, isActiveViolation }">
            <InputText
              v-model="state.nestedDemoEntity.name"
              type="text"
              :placeholder="
                $t('components.demo.validation.form.nestedDemoEntityName')
              "
              :class="{
                'p-invalid': isActiveViolation,
              }"
              @input="updated"
            />
            <label for="validation-textField">{{
              $t("components.demo.validation.form.nestedDemoEntityName")
            }}</label>
          </template>
        </ViolationField>
        <div class="col-12">
          <Button
            outlined
            class="mr-2 mb-2 inline-block col-12 lg:col-1"
            icon="pi pi-plus"
            @click.prevent.stop="createNewChild"
            ><i class="pi pi-plus"></i> Add</Button
          >
        </div>
        <div class="flex justify-content-between flex-wrap col-12">
          <Card
            v-for="(nested, index) in state.nestedDemoEntityList"
            :key="index"
            class="col col-6"
          >
            <template #content>
              <ViolationField
                class="field col-6"
                :violation="violations.nestedDemoEntityList?.[index]?.name"
              >
                <template #default="{ updated, isActiveViolation }">
                  <InputText
                    v-model="nested.name"
                    type="text"
                    :placeholder="
                      $t('components.demo.validation.form.textField')
                    "
                    :class="{
                      'p-invalid': isActiveViolation,
                    }"
                    @update:model-value="updated"
                  />
                  <label>{{
                    $t("components.demo.validation.form.textField")
                  }}</label>
                </template>
              </ViolationField>
            </template>
          </Card>
        </div>
      </div>
      <div>
        <Button type="submit" class="mr-2 mb-2">
          {{ $t("components.user.form.ok") }}
        </Button>
      </div>
    </form>
  </div>
</template>
<script lang="ts" setup>
import type { Violations } from "~/types/Violations";
import ViolationField from "~/components/form/ViolationField.vue";

interface Props {
  defaultValue?: State;
  violations?: Violations<State>;
}
const props = withDefaults(defineProps<Props>(), {
  defaultValue() {
    return createDefault();
  },
  violations() {
    return {};
  },
});
interface EventEmitter {
  (e: "submit", value: State): void;
  (e: "cancel"): void;
}

const emits = defineEmits<EventEmitter>();
const state = reactive({ ...props.defaultValue });
const createNewChild = () => {
  state.nestedDemoEntityList.push(createDefaultNestedEntity());
};
const submit = () => {
  emits("submit", state);
};
const createDefaultNestedEntity = () => {
  return {
    name: "",
  };
};
</script>

<script lang="ts">
function createDefault() {
  return {
    textField: "",
    siret: "",
    startDate: "",
    nestedDemoEntity: {
      name: "",
    },
    nestedDemoEntityList: [] as Array<NestedDemoEntity>,
  };
}

interface NestedDemoEntity {
  name: string;
}

interface State extends ReturnType<typeof createDefault> {}
export default {};
</script>
