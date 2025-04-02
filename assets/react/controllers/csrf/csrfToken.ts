import {useEffect, useState} from "react";

export function useCsrfToken(action: string) {
    const [csrfToken, setCsrfToken] = useState<string | null>(null);

    useEffect(() => {
        const fetchCsrfToken = async () => {
            try {
                const response = await fetch("/csrf-token", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({tokenId: "registration_form"}),
                });

                if (!response.ok) throw new Error("Erreur lors de la récupération du token CSRF");

                const data = await response.json();
                console.log(data.csrf_token)
                setCsrfToken(data.csrf_token);
            } catch (error) {
                console.error(error);
            }
        };

        fetchCsrfToken();
    }, []);
}