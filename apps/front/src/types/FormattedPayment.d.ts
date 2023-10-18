import { Payment } from "~/types/Payment";

export interface FormattedPayment extends Payment {
  amount: string;
  location: string | "NO_LOCATION";
  showLocationButton?: boolean;
}
