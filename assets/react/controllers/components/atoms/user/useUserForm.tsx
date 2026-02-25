import * as z from "zod";
import { User } from "../../../models/user/user.model";
import { useEntityForm } from "../../../hooks/useEntityForm";
import { errorMessages } from "../../../constants/texts";

const schema = z.object({
  firstname: z.string(),
  lastname: z.string(),
  job: z.string(),
  linkedin: z.string().optional(),
  age: z.string(),
  city: z.string(),
  phone: z
    .string()
    .min(10, "Le numéro de téléphone est invalide")
    .optional(),
});

export type UserFormInput = z.infer<typeof schema>;

export function useUserForm(
  action: string,
  csrfToken: string,
  initialValues: UserFormInput,
  onSubmitSuccess?: (data: User) => void
) {
  const form = useEntityForm<UserFormInput, { user: User }>({
    schema,
    action,
    csrfToken,
    initialValues,
    resultKey: "user",
    onSubmitSuccess,
    method: "POST",
    extraBody: { _method: "PUT" },
  });

  return {
    register: form.register,
    handleSubmit: form.handleSubmit,
    onSubmit: form.onSubmit,
    errors: form.formState.errors,
    isSubmitting: form.formState.isSubmitting,
    reset: form.reset,
  };
}