import { gql } from 'graphql-request'

export const VerifyResetPasswordTokenMutation = gql`
  mutation verifyResetPasswordToken(
    $resetPasswordTokenId: String!
    $plainToken: String!
  ) {
    verifyResetPasswordToken(
      resetPasswordTokenId: $resetPasswordTokenId
      plainToken: $plainToken
    )
  }
`
