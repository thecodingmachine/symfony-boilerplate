import { gql } from 'graphql-request'

export const UpdateProfilePictureMutation = gql`
  mutation updateProfilePicture($profilePicture: Upload!) {
    updateProfilePicture(profilePicture: $profilePicture) {
      profilePicture
    }
  }
`
