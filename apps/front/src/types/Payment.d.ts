import { DateTimeFormatResult } from "@nuxtjs/i18n/dist/runtime/composables";

export type PaymentId = number;
export interface Payment {
  id: PaymentId;
  label: string;
  amount: float;
  location: string;
  gps_position: string;
  created_at: DateTimeFormatResult;
}
