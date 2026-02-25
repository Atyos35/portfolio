import * as z from "zod";
import { Experience } from "../../../models/experiences/experience.model";
import { useEntityForm } from "../../../hooks/useEntityForm";
import { errorMessages } from "../../../constants/texts";

const schema = z
  .object({
    id: z.number().optional(),
    name: z.string(),
    start_date: z.string(),
    end_date: z.preprocess(
      (val) => {
        if (val === "" || val === null) return undefined;
        return val;
      },
      z.string().optional()
    ),
    duration: z
      .object({
        y: z.number(),
        m: z.number(),
        d: z.number(),
      })
      .optional(),
    description: z.string(),
    company: z.string(),
    city: z.string(),
  })
  .refine(
    (data) => {
      if (!data.end_date) return true;
      return new Date(data.start_date) <= new Date(data.end_date);
    },
    {
      message: errorMessages.dates.endBeforeStart,
      path: ["end_date"],
    }
  );

export type ExperienceFormInput = z.infer<typeof schema>;

export function useExperienceForm(
  action: string,
  csrfToken: string,
  initialValues: Partial<Experience> = {},
  onSubmitSuccess?: (data: Experience) => void
) {
  return useEntityForm<ExperienceFormInput, { experience: Experience }>({
    schema,
    action,
    csrfToken,
    initialValues,
    resultKey: "experience",
    onSubmitSuccess,
    method: "POST",
  });
}