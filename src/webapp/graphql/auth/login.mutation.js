import { gql } from 'graphql-request'
import { MeFragment } from '@/graphql/auth/me.fragment'

export const LoginMutation = gql`
  mutation login($userName: String!, $password: String!) {
    login(userName: $userName, password: $password) {
      ... on User {
        ...MeFragment
      }
    }
  }
  ${MeFragment}
`
