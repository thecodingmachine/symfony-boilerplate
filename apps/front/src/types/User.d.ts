export type UserId = number;
export interface User {
  id: UserId;
  email: string;
  score: float;
  first_name: string;
  last_name: string;
  payments_pending: float;
  roles: any[];
}
