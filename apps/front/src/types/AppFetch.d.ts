export type AppFetch<T> = (
  request: NitroFetchRequest,
  opts?: NitroFetchOptions<"json">,
) => Promise<T>;
