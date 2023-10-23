import type { User } from "~/types/User";

export default function useUser(user: Ref<User> | undefined = undefined) {
  const password = ref("");
  const passwordConfirm = ref("");
  // This wont be kept in sync if user is modified! (to resolve)
  const email = user ? toRef(user?.value?.email) : ref("");
  const isPasswordConfirmed = computed(
    () => password.value === passwordConfirm.value
  );

  const isPasswordEmpty = computed(() => !password.value);
  const securedPassword = computed(() =>
    isPasswordConfirmed && isPasswordEmpty ? password.value : ""
  );
  return {
    password,
    passwordConfirm,
    email: email,
    isPasswordConfirmed,
    securedPassword,
  };
}
