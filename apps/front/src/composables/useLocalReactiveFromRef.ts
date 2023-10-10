/**
 * This composable is usefull if you want to create a copy from a nuxt fetch
 * Better to use reactive to track objets
 * **/
export default function useLocalReactiveFromRef<T extends object>(
  originalRef: Ref<T> | T
): T {
  const val = unref(originalRef);
  const localReactive = reactive(val) as T;
  watchEffect(() => {
    const val = unref(originalRef);
    for (const key in val) {
      localReactive[key] = val[key];
    }
  });
  return localReactive;
}
