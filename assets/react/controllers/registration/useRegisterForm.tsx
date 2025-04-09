import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import * as Turbo from "@hotwired/turbo";

const schema = z.object({
    email: z.string().email("Invalid email"),
    firstname: z.string(),
    lastname: z.string(),
    job: z.string(),
    linkedin: z.string(),
    age: z.coerce.number(),
    city: z.string(),
    phone: z.string().min(10, "The phone number is invalid"),
    plainPassword: z.string()
        .min(9, "Password must contain at least 9 characters")
        .regex(/\d/, "Password must contain at least one number"),
});

export function useRegisterForm(action: string, csrfToken: string) {

    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm({
        resolver: zodResolver(schema),
    });

    const onSubmit = async (data: any) => {
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

            if (!response.ok) {
                throw new Error("Erreur d'inscription");
            }
            Turbo.visit('/confirmed-registration')
        } catch (error) {
            console.error(error);
        }
    };

    return { register, handleSubmit, errors, onSubmit };
}
