import { gql } from 'graphql-request'

export const ResetPasswordMutation = gql`
  mutation resetPassword($email: String!) {
    resetPassword(email: $email)
  }
`
