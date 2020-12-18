<template>
  <b-row>
    <b-col md="6">
      <b-card>
        <h1>
          {{ user.firstName + ' ' + user.lastName }}
        </h1>
        <ul class="list-unstyled">
          <li>
            <strong>{{ user.email }}</strong>
          </li>
          <li>
            <b-badge pill :variant="roleColorVariantFromEnum(user.role)">{{
              roleTranslationFromEnum(user.role)
            }}</b-badge>
          </li>
        </ul>
      </b-card>
    </b-col>
    <b-col class="mt-3 mt-md-0">
      <b-card>
        <b-row
          v-if="user.profilePicture !== null"
          class="justify-content-center mb-3"
        >
          <b-img
            :src="userProfilePictureURL(user.profilePicture)"
            rounded="circle"
            style="width: 10rem; height: 10rem; border: 1px solid black"
            :alt="$t('common.user.profile_picture')"
          ></b-img>
        </b-row>
        <b-form @submit.stop.prevent="onSubmit">
          <b-form-group
            id="input-group-profile-picture"
            :label="$t('common.user.profile_picture')"
            label-for="input-profile-picture"
          >
            <b-form-file
              id="input-profile-picture"
              v-model="form.profilePicture"
              :placeholder="$t('common.single_file.placeholder')"
              :drop-placeholder="$t('common.single_file.drop_placeholder')"
              :browse-text="$t('common.browse')"
              :state="formState('profile_picture')"
            ></b-form-file>
            <div v-if="form.profilePicture !== null" class="mt-3">
              <FilesList :files="[form.profilePicture]" />
            </div>
            <b-form-invalid-feedback :state="formState('profile_picture')">
              <ErrorsList :errors="formErrors('profile_picture')" />
            </b-form-invalid-feedback>
          </b-form-group>
          <b-button
            type="submit"
            variant="primary"
            :disabled="form.profilePicture === null"
          >
            {{ $t('common.update') }}
          </b-button>
        </b-form>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
import { Form } from '@/mixins/form'
import { Auth } from '@/mixins/auth'
import ErrorsList from '@/components/forms/ErrorsList'
import { Roles } from '@/mixins/roles'
import { Images } from '@/mixins/images'
import FilesList from '@/components/forms/FilesList'
import { GlobalOverlay } from '@/mixins/global-overlay'
import { UpdateProfilePictureMutation } from '@/graphql/users/update_profile_picture.mutation'

export default {
  components: { FilesList, ErrorsList },
  mixins: [Form, Auth, Roles, Images, GlobalOverlay],
  layout: 'dashboard',
  middleware: ['redirect-if-not-authenticated'],
  data() {
    return {
      form: {
        profilePicture: null,
      },
    }
  },
  methods: {
    async onSubmit() {
      this.resetFormErrors()
      this.displayGlobalOverlay()

      try {
        const result = await this.$graphql.request(
          UpdateProfilePictureMutation,
          {
            profilePicture: this.form.profilePicture,
          }
        )

        this.setUserProfilePicture(result.updateProfilePicture.profilePicture)
        this.form.profilePicture = null
      } catch (e) {
        this.hydrateFormErrors(e)
      } finally {
        this.hideGlobalOverlay()
      }
    },
  },
}
</script>
