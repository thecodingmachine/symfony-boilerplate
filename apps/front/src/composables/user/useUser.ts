import { User } from "~/types/user";

export default function useUser(user: Ref<User> | undefined = undefined) {
  const password = ref("");
  const passwordConfirm = ref("");
  const email = user ? toRef(user?.value?.email) : ref("");
  const isPasswordConfirmed = computed(
    () => password.value === passwordConfirm.value
  );

  const isPasswordEmpty = computed(() => !password.value);
  return {
    password,
    passwordConfirm,
    email: email,
    isPasswordConfirmed,
    securedPassword: computed(() =>
      isPasswordConfirmed && isPasswordEmpty ? password.value : ""
    ),
  };
}
