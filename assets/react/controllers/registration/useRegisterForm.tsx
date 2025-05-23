import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import * as Turbo from "@hotwired/turbo";

const schema = z
  .object({
    email: z.string().email("Adresse email invalide"),
    firstname: z.string(),
    lastname: z.string(),
    job: z.string(),
    linkedin: z.string(),
    age: z.coerce.number(),
    city: z.string(),
    phone: z.string().min(10, "Le numéro de téléphone est invalide"),
    plainPassword: z
      .string()
      .min(9, "Le mot de passe doit contenir au moins 9 caractères")
      .regex(/\d/, "Le mot de passe doit contenir au moins un nombre"),
    confirmPassword: z.string(),
  })
  .refine((data) => data.plainPassword === data.confirmPassword, {
    message: "Les mots de passe ne correspondent pas",
    path: ["confirmPassword"],
  });

export function useRegisterForm(action: string, csrfToken: string) {
  const {
    register,
    handleSubmit,
    watch,
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
      const { confirmPassword, ...sendData } = data;

      const response = await fetch(action, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ...sendData, _csrf_token: csrfToken }),
      });

      if (!response.ok) {
        throw new Error("Erreur d'inscription");
      }
      Turbo.visit("/confirmed-registration");
    } catch (error) {
      console.error(error);
    }
  };

  return { register, handleSubmit, watch, errors, onSubmit };
}