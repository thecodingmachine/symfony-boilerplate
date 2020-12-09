import { FailIfNotAuthenticatedQuery } from '@/graphql/auth/fail_if_not_authenticated.query'

export default async function ({ app, error }) {
  try {
    await app.$graphql.request(FailIfNotAuthenticatedQuery)
  } catch (e) {
    error(e)
  }
}
