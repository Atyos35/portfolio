import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import * as Turbo from "@hotwired/turbo";

const schema = z.object({
    firstname: z.string().optional(),
    lastname: z.string().optional(),
    job: z.string().optional(),
    linkedin: z.string().optional(),
    age: z.string().optional(),
    city: z.string().optional(),
    phone: z.string().min(10, "Le numéro de téléphone est invalide").optional(),
});

export type UserFormInput = z.infer<typeof schema>;

export function useUserForm(action: string, csrfToken: string) {
    const {
        register,
        handleSubmit,
        formState: { errors, isSubmitting },
        reset,
    } = useForm({
        resolver: zodResolver(schema),
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
                body: JSON.stringify({ ...data, _csrf_token: csrfToken }),
            });

            const res = await response.json();
            if (!response.ok) {
                throw new Error(res.message || "Erreur lors de la mise à jour");
            }

            Turbo.visit("/");
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