import { User } from "~/types/User";
// This is used for form communication
type UserInput = Omit<User, "id"> & {
  password: string;
};
