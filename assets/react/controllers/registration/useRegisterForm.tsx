import { useState, useEffect } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";

const schema = z.object({
    email: z.string().email("Email invalide"),
    firstname: z.string(),
    lastname: z.string(),
    job: z.string(),
    linkedin: z.string(),
    age: z.number().min(6, "L'âge doit être en années"),
    city: z.string(),
    phone: z.string().min(10, "Le numéro de téléphone n'est pas valide"),
    plainPassword: z.string().min(6, "Mot de passe trop court"),
});

export function useRegisterForm(action: string) {
    const [csrfToken, setCsrfToken] = useState<string | null>(null);

    // Récupération du token CSRF au montage du composant
    useEffect(() => {
        const fetchCsrfToken = async () => {
            try {
                const response = await fetch("/api/csrf-token");
                if (!response.ok) throw new Error("Erreur lors de la récupération du token CSRF");

                const data = await response.json();
                setCsrfToken(data.csrf_token);
            } catch (error) {
                console.error(error);
            }
        };

        fetchCsrfToken();
    }, []);

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

            alert("Inscription réussie !");
        } catch (error) {
            console.error(error);
        }
    };

    return { register, handleSubmit, errors, onSubmit };
}
