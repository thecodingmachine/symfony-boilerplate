<template>
  <div>
    <b-card>
      <b-form inline @submit.stop.prevent>
        <label class="sr-only" for="inline-form-input-search">{{
          $t('common.search')
        }}</label>
        <b-input
          id="inline-form-input-search"
          v-model="filters.search"
          class="mb-2 mr-sm-2 mb-sm-0"
          style="min-width: 50%"
          type="text"
          :placeholder="$t('common.search')"
          autofocus
          trim
          :debounce="debounce"
          @update="onSearch"
        ></b-input>
        <label class="sr-only" for="inline-form-select-role">{{
          $t('common.user.role')
        }}</label>
        <b-form-select
          id="inline-form-select-role"
          v-model="filters.role"
          class="mb-2 mr-sm-2 mb-sm-0"
          :options="rolesAsSelectOptions(true)"
          @change="onSearch"
        >
        </b-form-select>
        <div class="m-auto mr-sm-0 ml-sm-auto">
          <b-button
            variant="primary"
            :to="localePath({ name: 'dashboard-admin-users-create' })"
            >{{ $t('common.create') }}</b-button
          >
          <b-button
            variant="outline-primary"
            :href="$config.apiURL + 'users/xlsx' + rawQueryParameters()"
            >{{ $t('common.export') }}</b-button
          >
        </div>
      </b-form>
    </b-card>
    <b-card class="mt-3">
      <b-table
        hover
        :responsive="true"
        :no-local-sorting="true"
        :sort-by="boostrapTableSortBy"
        :sort-desc="isDesc"
        :items="items"
        :fields="fields"
        :busy="isLoading"
        @sort-changed="onSort"
      >
        <template #cell(profilePicture)="data">
          <b-img
            :src="
              data.item.profilePicture !== null
                ? userProfilePictureURL(data.item.profilePicture)
                : defaultProfilePictureURL
            "
            rounded="circle"
            style="width: 3rem; height: 3rem; border: 1px solid black"
            :alt="$t('common.user.profile_picture')"
          ></b-img>
        </template>
        <template #cell(role)="data">
          {{ roleTranslationFromEnum(data.item.role) }}
        </template>
        <template #cell(actions)="data">
          <b-button
            size="sm"
            variant="primary"
            :aria-label="$t('common.edit')"
            :to="
              localePath({
                name: 'dashboard-admin-users-id',
                params: { id: data.item.id },
              })
            "
          >
            <b-icon icon="pencil"></b-icon>
          </b-button>
        </template>
        <template #table-busy>
          <div class="text-center my-2">
            <b-spinner class="align-middle" variant="primary"></b-spinner>
          </div>
        </template>
      </b-table>
      <b-pagination
        v-model="currentPage"
        :per-page="itemsPerPage"
        :total-rows="count"
        align="center"
        @change="onPaginate"
        @click.native="$scrollToTop"
      />
    </b-card>
  </div>
</template>

<script>
import { Form } from '@/mixins/form'
import { List, defaultItemsPerPage, calculateOffset } from '@/mixins/list'
import { Roles } from '@/mixins/roles'
import { EMAIL, FIRST_NAME, LAST_NAME } from '@/enums/filters/users-sort-by'
import { UsersQuery } from '@/graphql/users/users.query'
import { Images } from '@/mixins/images'

export default {
  mixins: [Form, List, Roles, Images],
  layout: 'dashboard',
  async asyncData(context) {
    try {
      const result = await context.app.$graphql.request(UsersQuery, {
        search: context.route.query.search || '',
        role: context.route.query.role || null,
        sortBy: context.route.query.sortBy || null,
        sortOrder: context.route.query.sortOrder || null,
        limit: defaultItemsPerPage,
        offset: calculateOffset(
          context.route.query.page || 1,
          defaultItemsPerPage
        ),
      })
      return {
        items: result.users.items,
        count: result.users.count,
      }
    } catch (e) {
      context.error(e)
    }
  },
  data() {
    return {
      filters: {
        search: this.$route.query.search || '',
        role: this.$route.query.role || null,
      },
      fields: [
        { key: 'profilePicture', label: '', sortable: false },
        {
          key: 'firstName',
          label: this.$t('common.user.first_name.label'),
          sortable: true,
        },
        {
          key: 'lastName',
          label: this.$t('common.user.last_name.label'),
          sortable: true,
        },
        { key: 'email', label: this.$t('common.email.label'), sortable: true },
        {
          key: 'locale',
          label: this.$t('common.user.locale.label'),
          sortable: false,
        },
        {
          key: 'role',
          label: this.$t('common.user.role.label'),
          sortable: false,
        },
        {
          key: 'actions',
          label: this.$t('common.list.actions'),
          sortable: false,
        },
      ],
      sortByMap: {
        firstName: FIRST_NAME,
        lastName: LAST_NAME,
        email: EMAIL,
      },
    }
  },
  methods: {
    async doSearch() {
      this.isLoading = true
      this.updateRouter()
      try {
        const result = await this.$graphql.request(UsersQuery, {
          search: this.filters.search,
          role: this.filters.role,
          sortBy: this.sortBy,
          sortOrder: this.sortOrder,
          limit: this.itemsPerPage,
          offset: this.offset,
        })
        this.items = result.users.items
        this.count = result.users.count
        this.isLoading = false
      } catch (e) {
        this.$nuxt.error(e)
      }
    },
  },
}
</script>
