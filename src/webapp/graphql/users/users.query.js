import { gql } from 'graphql-request'
import { MeFragment } from '@/graphql/auth/me.fragment'

export const UsersQuery = gql`
  query users(
    $search: String
    $role: Role
    $sortBy: UsersSortBy
    $sortOrder: SortOrder
    $limit: Int!
    $offset: Int!
  ) {
    users(
      search: $search
      role: $role
      sortBy: $sortBy
      sortOrder: $sortOrder
    ) {
      items(limit: $limit, offset: $offset) {
        ...MeFragment
      }
      count
    }
  }
  ${MeFragment}
`
