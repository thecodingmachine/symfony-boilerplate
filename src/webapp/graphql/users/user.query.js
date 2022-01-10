import { gql } from 'graphql-request'
import { MeFragment } from '@/graphql/auth/me.fragment'

export const UserQuery = gql`
  query user($id: String!) {
    user(user: { id: $id }) {
      ...MeFragment
    }
  }
  ${MeFragment}
`
