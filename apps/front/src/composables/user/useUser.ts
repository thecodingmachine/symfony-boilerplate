import { User } from "~/types/User";
import useLocalReactiveFromRef from "~/composables/useLocalReactiveFromRef";
export default function useUser(user: Ref<User> | undefined = undefined) {
  // Here we put the whole object as reactive state
  // We create a copy so when you update the email, it wont be reflected in initial data.
  // But when the email is updated in initial data, it update your local copy
  const localReactive = useLocalReactiveFromRef<User>(
    user || {
      email: "",
      id: 0,
    }
  );
  // Password has very specific rules. You should only need to have an useLocalReactiveFromRef
  const password = ref("");
  const passwordConfirm = ref("");
  const isPasswordConfirmed = computed(
    () => password.value === passwordConfirm.value
  );

  const isPasswordEmpty = computed(() => !password.value);
  return {
    localReactive: localReactive,
    password,
    passwordConfirm,
    isPasswordConfirmed,
    securedPassword: computed(() =>
      isPasswordConfirmed && isPasswordEmpty ? password.value : ""
    ),
  };
}
