import { Payment } from "~/types/Payment";
import { GET } from "~/constants/http";
import useAppFetch from "~/composables/useAppFetch";

export default async function useListPayments() {
  return useAppFetch<Array<Payment>>(() => "/payments", {
    key: "listPayments",
    method: GET,
    lazy: true,
  });
}
