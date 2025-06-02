export type User = {
  id: number;
  firstname: string;
  lastname: string;
  job: string;
  linkedin: string;
  email: string;
  city: string;
  age: string;
  phone: string;
  profilePicture: string;
};

export const emptyBlock: User = {
  id: 0,
  firstname: "",
  lastname: "",
  job: "",
  linkedin: "",
  email: "",
  city: "",
  age: "",
  phone: "",
  profilePicture: "",
};