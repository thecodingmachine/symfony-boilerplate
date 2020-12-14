import { gql } from 'graphql-request'

export const MeFragment = gql`
  fragment MeFragment on User {
    id
    firstName
    lastName
    email
    locale
    profilePicture
    role
  }
`
