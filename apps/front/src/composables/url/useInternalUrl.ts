export default function useInternalUrl() {
  const requestUrl = useRequestURL();
  const origin = requestUrl.origin;

  return {
    isInternalUrl(url: string): boolean {
      return url.startsWith(origin);
    },
  };
}
