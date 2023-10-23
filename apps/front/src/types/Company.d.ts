import {User} from "~/types/User";

export type CompanyId = number;
export interface Company {
  id: CompanyId;
  name: string;
  users: Array<User>;
  identityFile: File|null;
}
