<template>
  <div>
    <div v-if="!showConfirmDeleteForm">
      <p>
        {{ $t('components.forms.confirm_delete.danger_zone_message') }}
      </p>
      <b-button
        variant="outline-danger"
        @click="showConfirmDeleteForm = true"
        >{{ $t('common.delete') }}</b-button
      >
    </div>
    <div v-else>
      <b-form inline @submit.stop.prevent="onSubmit">
        <b-form-group
          id="input-group-confirm-delete"
          :label="$t('components.forms.confirm_delete.enter_confirm')"
          label-for="input-confirm-delete"
          class="text-danger"
        >
          <b-form-input
            id="input-confirm-delete"
            v-model="form.confirm"
            type="text"
            trim
            required
            :state="formState"
            class="ml-sm-3"
          />
        </b-form-group>
        <div class="mr-sm-0 ml-sm-auto">
          <b-button variant="outline-dark" @click="onCancel">
            {{ $t('common.cancel') }}
          </b-button>
          <b-button type="submit" variant="outline-danger">
            {{ $t('common.delete') }}
          </b-button>
        </div>
      </b-form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ConfirmDelete',
  props: {
    onConfirm: {
      type: Function,
      required: true,
    },
  },
  data() {
    return {
      showConfirmDeleteForm: false,
      form: {
        confirm: '',
      },
      formState: null,
    }
  },
  methods: {
    onSubmit() {
      if (this.form.confirm !== this.$t('common.confirm')) {
        this.formState = false
        return
      }

      this.onConfirm()
    },
    onCancel() {
      this.showConfirmDeleteForm = false
      this.form.confirm = ''
      this.formState = null
    },
  },
}
</script>
