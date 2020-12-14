import { gql } from 'graphql-request'

export const DeleteUserMutation = gql`
  mutation deleteUser($id: String!) {
    deleteUser(user1: { id: $id })
  }
`
