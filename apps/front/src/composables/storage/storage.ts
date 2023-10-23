export const publicFileURL = (filePath: string) => {
  const config = useRuntimeConfig();
  return config.public.publicStorageUrl + filePath;
};
