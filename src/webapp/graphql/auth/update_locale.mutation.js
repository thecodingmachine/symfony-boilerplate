import { gql } from 'graphql-request'

export const UpdateLocaleMutation = gql`
  mutation updateLocale($locale: Locale!) {
    updateLocale(locale: $locale) {
      locale
    }
  }
`
