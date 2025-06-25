import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import * as Turbo from "@hotwired/turbo";

  const schema = z
    .object({
      email: z.string().email("Adresse email invalide").nonempty("L'email est obligatoire"),
      firstname: z.string().nonempty("Le prénom est obligatoire"),
      lastname: z.string().nonempty("Le nom est obligatoire"),
      job: z.string().nonempty("Le poste est obligatoire"),
      linkedin: z.string().nonempty("Le LinkedIn est obligatoire"),
      age: z.coerce.number().min(1, "L'âge est obligatoire"),
      city: z.string().nonempty("La ville est obligatoire"),
      phone: z.string().min(10, "Le numéro de téléphone est invalide").nonempty("Le téléphone est obligatoire"),
      plainPassword: z
        .string()
        .min(9, "Le mot de passe doit contenir au moins 9 caractères")
        .regex(/\d/, "Le mot de passe doit contenir au moins un nombre"),
      confirmPassword: z.string().nonempty("La confirmation du mot de passe est obligatoire"),
    })
    .refine((data) => data.plainPassword === data.confirmPassword, {
      message: "Les mots de passe ne correspondent pas",
      path: ["confirmPassword"],
  });

import { useState } from "react";

export function useRegisterForm(action: string, csrfToken: string) {
  const {
    register,
    handleSubmit,
    watch,
    formState: { errors },
  } = useForm({
    resolver: zodResolver(schema),
  });

  const [isSubmitting, setIsSubmitting] = useState(false);
  const [success, setSuccess] = useState(false);
  const [isRedirecting, setIsRedirecting] = useState(false);

  const onSubmit = async (data: any) => {
    if (!csrfToken) {
      console.error("Token CSRF non disponible");
      return;
    }

    setIsSubmitting(true);
    setSuccess(false);

    const { confirmPassword, ...sendData } = data;

    try {
      const response = await fetch(action, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ...sendData, _csrf_token: csrfToken }),
      });

      if (!response.ok) throw new Error("Erreur d'inscription");

      setSuccess(true);
      setTimeout(() => {
        setIsRedirecting(true);
      }, 1000);

      setTimeout(() => {
        Turbo.visit("/login");
      }, 2000);
    } catch (error) {
      console.error(error);
    } finally {
      setIsSubmitting(false);
    }
  };

  return { register, handleSubmit, watch, errors, onSubmit, isSubmitting, success, isRedirecting };
}