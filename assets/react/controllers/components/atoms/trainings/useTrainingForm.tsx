import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import * as Turbo from "@hotwired/turbo";
import { Training } from "../../../models/trainings/training.model";

const schema = z.object({
  id: z.number().optional(),
  name: z.string(),
  start_date: z.string(),
  end_date: z
    .preprocess((val) => {
      if (val === "" || val === null) return undefined;
      return val;
    }, z.string().optional()),
  duration: z.object({
    y: z.number(),
    m: z.number(),
    d: z.number(),
  }).optional(),
  description: z.string(),
  school: z.string(),
  city: z.string(),
}).refine(
  (data) => {
    if (!data.end_date) return true;
    return new Date(data.start_date) <= new Date(data.end_date);
  },
  {
    message: "La date de fin ne peut pas être antérieure à la date de début.",
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
    const {
      register,
      handleSubmit,
      formState: { errors, isSubmitting },
      reset,
      setValue,
    } = useForm<TrainingFormInput>({
      resolver: zodResolver(schema),
      defaultValues: initialValues,
    });
  
    const onSubmit = async (data: TrainingFormInput) => {
      try {
        const response = await fetch(action, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            ...data,
            _csrf_token: csrfToken,
          }),
        });
  
        const result = await response.json();
            if (!response.ok) throw new Error("Erreur lors de la mise à jour");

        onSubmitSuccess?.(result.training);
      } catch (error) {
        console.error(error);
      }
    };
  
    return {
      register,
      handleSubmit,
      onSubmit,
      errors,
      isSubmitting,
      reset,
      setValue,
    };
}