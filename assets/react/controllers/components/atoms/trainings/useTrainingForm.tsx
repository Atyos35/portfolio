import * as z from "zod";
import { Training } from "../../../models/trainings/training.model";
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
    school: z.string(),
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

export type TrainingFormInput = z.infer<typeof schema>;

export function useTrainingForm(
  action: string,
  csrfToken: string,
  initialValues: Partial<Training> = {},
  onSubmitSuccess?: (data: Training) => void
) {
  return useEntityForm<TrainingFormInput, { training: Training }>({
    schema,
    action,
    csrfToken,
    initialValues,
    resultKey: "training",
    onSubmitSuccess,
    method: "POST",
  });
}