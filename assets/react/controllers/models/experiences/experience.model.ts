export type Experience = {
    id: number;
    name: string;
    start_date: string;
    end_date: string;
    duration?: {
      y: number;
      m: number;
      d: number;
    };
    description: string;
    company: string;
    city: string;
  };  

export const emptyExperience: Experience = {
    id: 0,
    name: "",
    start_date: "",
    end_date: "",
    duration: {
      y: 0,
      m: 0,
      d: 0
    },
    description: "",
    company: "",
    city: ""
  };  