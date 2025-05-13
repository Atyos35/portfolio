export type Block = {
    id: number;
    title: string;
    names: string[];
  };  

export const emptyBlock: Block = {
    id: 0,
    title: "",
    names: [],
  };  