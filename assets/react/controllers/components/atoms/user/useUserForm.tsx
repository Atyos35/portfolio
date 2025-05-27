import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import { User } from "../../../models/user/user.model";

const schema = z.object({
    firstname: z.string(),
    lastname: z.string(),
    job: z.string(),
    linkedin: z.string().optional(),
    age: z.string(),
    city: z.string(),
    phone: z.string().min(10, "Le numéro de téléphone est invalide").optional(),
});

export type UserFormInput = z.infer<typeof schema>;

export function useUserForm(
    action: string,
    csrfToken: string,
    initialValues: UserFormInput,
    onSubmitSuccess?: (data: User) => void
  ) {
    const {
      register,
      handleSubmit,
      formState: { errors, isSubmitting },
      reset,
    } = useForm<UserFormInput>({
      resolver: zodResolver(schema),
      defaultValues: initialValues,
    });

    const onSubmit = async (data: UserFormInput) => {
        if (!csrfToken) {
            console.error("Token CSRF non disponible");
            return;
        }

        try {
            const response = await fetch(action, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                ...data,
                _method: 'PUT',
                _csrf_token: csrfToken,
            }),
            });

            const result = await response.json();
            if (!response.ok) throw new Error("Erreur lors de la mise à jour");

            onSubmitSuccess?.(result.user);
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
    };
}