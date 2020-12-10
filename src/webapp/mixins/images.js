import logo from '@/assets/images/logo.svg'
import defaultProfilePicture from '@/assets/images/default-profile-picture.svg'
import defaultProfilePictureWhite from '@/assets/images/default-profile-picture-white.svg'

export const Images = {
  data() {
    return {
      logoImageURL: logo,
      defaultProfilePictureURL: defaultProfilePicture,
      defaultProfilePictureWhiteURL: defaultProfilePictureWhite,
    }
  },
  methods: {
    userProfilePictureURL(filename) {
      return this.$config.apiURL + 'users/profile-picture/' + filename
    },
  },
}
