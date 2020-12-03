import { gql } from 'graphql-request'

export const UpdatePasswordMutation = gql`
  mutation updatePassword(
    $resetPasswordTokenId: String!
    $plainToken: String!
    $newPassword: String!
    $passwordConfirmation: String!
  ) {
    updatePassword(
      resetPasswordToken: { id: $resetPasswordTokenId }
      plainToken: $plainToken
      newPassword: $newPassword
      passwordConfirmation: $passwordConfirmation
    ) {
      email
    }
  }
`
