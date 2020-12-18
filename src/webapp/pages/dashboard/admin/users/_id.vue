<template>
  <div>
    <b-card>
      <b-form @submit.stop.prevent="onSubmit">
        <b-form-row>
          <b-col md="6">
            <b-form-group
              id="input-group-first-name"
              :label="$t('common.user.first_name.label_required')"
              label-for="input-first-name"
            >
              <b-form-input
                id="input-first-name"
                v-model="form.firstName"
                type="text"
                :placeholder="$t('common.user.first_name.placeholder')"
                autofocus
                trim
                required
                :state="formState('firstName')"
              />
              <b-form-invalid-feedback :state="formState('firstName')">
                <ErrorsList :errors="formErrors('firstName')" />
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
          <b-col>
            <b-form-group
              id="input-group-last-name"
              :label="$t('common.user.last_name.label_required')"
              label-for="input-last-name"
            >
              <b-form-input
                id="input-last-name"
                v-model="form.lastName"
                type="text"
                :placeholder="$t('common.user.last_name.placeholder')"
                trim
                required
                :state="formState('lastName')"
              />
              <b-form-invalid-feedback :state="formState('lastName')">
                <ErrorsList :errors="formErrors('lastName')" />
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>
          <b-col md="6">
            <b-form-group
              id="input-group-email"
              :label="$t('common.email.label_required')"
              label-for="input-email"
            >
              <b-form-input
                id="input-email"
                v-model="form.email"
                type="text"
                :placeholder="$t('common.email.placeholder')"
                trim
                required
                :state="formState('email')"
              />
              <b-form-invalid-feedback :state="formState('email')">
                <ErrorsList :errors="formErrors('email')" />
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
          <b-col v-if="!selfUpdate">
            <b-form-group
              id="input-group-locale"
              :label="$t('common.user.locale.label_required')"
              label-for="input-locale"
            >
              <b-form-select
                id="input-locale"
                v-model="form.locale"
                :options="localesAsSelectOptions()"
                required
                :state="formState('locale')"
              ></b-form-select>
              <b-form-invalid-feedback :state="formState('locale')">
                <ErrorsList :errors="formErrors('locale')" />
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
          <b-col>
            <b-form-group
              id="input-group-role"
              :label="$t('common.user.role.label_required')"
              label-for="input-role"
            >
              <b-form-select
                id="input-role"
                v-model="form.role"
                :options="rolesAsSelectOptions()"
                required
                :state="formState('role')"
              ></b-form-select>
              <b-form-invalid-feedback :state="formState('role')">
                <ErrorsList :errors="formErrors('role')" />
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row
          v-if="currentProfilePicture !== null"
          class="justify-content-center mt-3 mb-3"
        >
          <b-img
            :src="userProfilePictureURL(currentProfilePicture)"
            rounded="circle"
            style="width: 10rem; height: 10rem; border: 1px solid black"
            :alt="$t('common.user.profile_picture')"
          ></b-img>
        </b-form-row>
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
        <b-button type="submit" variant="primary">
          {{ $t('common.update') }}
        </b-button>
      </b-form>
    </b-card>
    <b-card v-if="!selfUpdate" class="mt-3" border-variant="danger">
      <ConfirmDelete :on-confirm="onDelete" />
    </b-card>
  </div>
</template>

<script>
import { UserQuery } from '@/graphql/users/user.query'
import { UpdateUserMutation } from '@/graphql/users/update_user.mutation'
import { Form } from '@/mixins/form'
import { Roles } from '@/mixins/roles'
import { Locales } from '@/mixins/locales'
import { GlobalOverlay } from '@/mixins/global-overlay'
import { Auth } from '@/mixins/auth'
import { Images } from '@/mixins/images'
import { GenericToast } from '@/mixins/generic-toast'
import ErrorsList from '@/components/forms/ErrorsList'
import FilesList from '@/components/forms/FilesList'
import { DeleteUserMutation } from '@/graphql/users/delete_user.mutation'
import ConfirmDelete from '@/components/forms/ConfirmDelete'

export default {
  components: { ConfirmDelete, FilesList, ErrorsList },
  mixins: [Form, Roles, Locales, GlobalOverlay, Auth, Images, GenericToast],
  layout: 'dashboard',
  async asyncData(context) {
    try {
      const result = await context.app.$graphql.request(UserQuery, {
        id: context.params.id,
      })

      return {
        currentProfilePicture: result.user.profilePicture,
        form: {
          firstName: result.user.firstName,
          lastName: result.user.lastName,
          email: result.user.email,
          locale: result.user.locale.toUpperCase(),
          role: result.user.role,
          profilePicture: null,
        },
      }
    } catch (e) {
      context.error(e)
    }
  },
  data() {
    return {
      currentProfilePicture: null,
      form: {
        firstName: '',
        lastName: '',
        email: '',
        locale: null,
        role: null,
        profilePicture: null,
      },
    }
  },
  computed: {
    selfUpdate() {
      return this.user.id === this.$route.params.id
    },
  },
  methods: {
    async onSubmit() {
      this.resetFormErrors()
      this.displayGlobalOverlay()

      try {
        const result = await this.$graphql.request(UpdateUserMutation, {
          id: this.$route.params.id,
          firstName: this.form.firstName,
          lastName: this.form.lastName,
          email: this.form.email,
          locale: this.selfUpdate
            ? this.user.locale.toUpperCase()
            : this.form.locale,
          role: this.form.role,
          profilePicture: this.form.profilePicture,
        })

        this.currentProfilePicture = result.updateUser.profilePicture
        this.form.profilePicture = null
        this.genericSuccessToast()

        // Same user as authenticated one.
        if (this.selfUpdate) {
          this.setUser(result.updateUser)
        }
      } catch (e) {
        this.hydrateFormErrors(e)
      } finally {
        this.hideGlobalOverlay()
      }
    },
    async onDelete() {
      this.displayGlobalOverlay()

      try {
        await this.$graphql.request(DeleteUserMutation, {
          id: this.$route.params.id,
        })

        this.genericSuccessToast()
        this.$router.push(this.localePath({ name: 'dashboard-admin-users' }))
      } catch (e) {
        this.$nuxt.error(e)
      } finally {
        this.hideGlobalOverlay()
      }
    },
  },
}
</script>
