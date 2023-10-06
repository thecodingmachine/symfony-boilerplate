import { useAuthUser } from "~/store/auth";

export default defineNuxtRouteMiddleware(async () => {
  const { $appFetch } = useNuxtApp();
  const app = usePinia();
  const authStore = useAuthUser(app);
  // We refresh the data information
  // If the syncMe result in a 401, the component RedirectToLogin will be triggered,
  // so no need to wait the sync
  const mePromise = authStore.refresh($appFetch);
  /**
   *   We still wait if the user is not authenticated because that may mean
   * the client has not retrieved user information
   *
   *   If we want to speed up a bit the process, we could check the status of the syncMe request,
   * to know if it has been done once aka if (authStore.hasBeenLoadedOnce)
   * */
  const shouldWait =
    process.server || (!authStore.isAuthenticated && process.client);
  if (!shouldWait) {
    return;
  }
  await mePromise;
});
