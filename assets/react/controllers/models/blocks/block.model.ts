export type Block = {
  id: number;
  title: string;
  names: { value: string }[];
};

export const emptyBlock: Block = {
  id: 0,
  title: "",
  names: [{ value: "" }],
};