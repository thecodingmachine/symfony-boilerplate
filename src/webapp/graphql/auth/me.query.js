import { gql } from 'graphql-request'
import { MeFragment } from '@/graphql/auth/me.fragment'

export const MeQuery = gql`
  query me {
    me {
      ... on User {
        ...MeFragment
      }
    }
  }
  ${MeFragment}
`
