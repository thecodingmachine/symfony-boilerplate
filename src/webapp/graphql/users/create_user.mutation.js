import { gql } from 'graphql-request'
import { MeFragment } from '@/graphql/auth/me.fragment'

export const CreateUserMutation = gql`
  mutation createUser(
    $firstName: String!
    $lastName: String!
    $email: String!
    $locale: Locale!
    $role: Role!
    $profilePicture: Upload
  ) {
    createUser(
      firstName: $firstName
      lastName: $lastName
      email: $email
      locale: $locale
      role: $role
      profilePicture: $profilePicture
    ) {
      ...MeFragment
    }
  }
  ${MeFragment}
`
