import { useAuthUser } from "~/store/auth";
import useInternalUrl from "~/composables/url/useInternalUrl";

export default defineNuxtRouteMiddleware(async (to) => {
  const authStore = useAuthUser();
  const internalUrl = useInternalUrl();
  if (!authStore.isAuthenticated) {
    return;
  }
  if (!to.query.returnTo) {
    return navigateTo("/");
  }
  const continueUrl = "" + to.query.returnTo;
  if (authStore.isAuthenticated && internalUrl.isInternalUrl(continueUrl)) {
    // Better to recheck route.query.returnTo because if route.query.returnTo is undefined, then continueUrl would be set as the string 'undefined' instead of the undefined type
    return navigateTo(to.query.returnTo && continueUrl ? continueUrl : "/", {
      external: true,
    });
  }
  return navigateTo("/");
});
