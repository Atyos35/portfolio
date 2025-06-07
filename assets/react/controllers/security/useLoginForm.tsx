import * as Turbo from "@hotwired/turbo";
import { useForm } from "react-hook-form";
import { useState } from "react";

export function useLoginForm(action: string, csrfToken: string) {
    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm();

    const [errorMessage, setErrorMessage] = useState("");

    const onSubmit = async (data: any) => {
        setErrorMessage("");
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
                setErrorMessage(json.message || "Identifiants incorrects.");
                return;
            }

            Turbo.visit("/");
        } catch (error) {
            console.error("Erreur de soumission :", error);
        }
    };

    return { register, handleSubmit, errors, onSubmit, errorMessage };
}