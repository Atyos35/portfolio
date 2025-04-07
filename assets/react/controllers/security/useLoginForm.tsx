import * as Turbo from "@hotwired/turbo";
import { useForm } from "react-hook-form";

export function useLoginForm(action: string, csrfToken: string) {
    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm();

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

            const json = await response.json();

            if (!response.ok) {
                console.error("Erreur côté serveur :", json);
                return;
            }

            Turbo.visit("/");
        } catch (error) {
            console.error("Erreur de soumission :", error);
        }
    };

    return { register, handleSubmit, errors, onSubmit };
}