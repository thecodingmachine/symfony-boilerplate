import { gql } from 'graphql-request'

export const FailIfNotAuthenticatedQuery = gql`
  query failIfNotAuthenticated {
    failIfNotAuthenticated
  }
`
