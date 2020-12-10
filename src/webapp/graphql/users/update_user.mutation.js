import { gql } from 'graphql-request'
import { MeFragment } from '@/graphql/auth/me.fragment'

export const UpdateUserMutation = gql`
  mutation updateUser(
    $id: String!
    $firstName: String!
    $lastName: String!
    $email: String!
    $locale: Locale!
    $role: Role!
    $profilePicture: Upload
  ) {
    updateUser(
      user: { id: $id }
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
