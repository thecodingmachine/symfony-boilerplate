import { gql } from 'graphql-request'

export const LogoutMutation = gql`
  mutation {
    logout
  }
`
