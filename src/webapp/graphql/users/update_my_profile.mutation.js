import { gql } from 'graphql-request'

export const UpdateMyProfile = gql`
  mutation updateMyProfile($profilePicture: Upload) {
    updateMyProfile(profilePicture: $profilePicture) {
      profilePicture
    }
  }
`
