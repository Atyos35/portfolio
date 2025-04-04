import {useEffect, useState} from "react";
import * as Turbo from "@hotwired/turbo";
import {useForm} from "react-hook-form";
import {zodResolver} from "@hookform/resolvers/zod";

export function useLoginForm(action: string) {
    const [csrfToken, setCsrfToken] = useState<string | null>(null);

    useEffect(() => {
        const fetchCsrfToken = async () => {
            try {
                const response = await fetch("/csrf-token", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ tokenId: "registration_form" }),
                });

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
    } = useForm({});

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