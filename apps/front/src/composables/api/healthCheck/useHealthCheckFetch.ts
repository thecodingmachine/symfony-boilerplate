import { FetchError } from "ofetch";
import type { AsyncData } from "#app";
import useAppFetch from "~/composables/useAppFetch";

interface LoggedHealthCheckResponse {
  success: string;
}
export function useHealthCheckFetch(): AsyncData<
  LoggedHealthCheckResponse | null,
  FetchError | null
> {
  return useAppFetch<LoggedHealthCheckResponse>(
    () => "/api/1.0/healthcheck/logged",
  );
}
