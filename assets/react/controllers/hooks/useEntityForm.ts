import { useForm, UseFormReturn } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { ZodSchema } from "zod";

type UseEntityFormOptions<TInput, TResult> = {
  schema: ZodSchema<TInput>;
  action: string;
  csrfToken: string;
  method?: "POST" | "PUT" | "PATCH";
  initialValues?: Partial<TInput>;
  /**
   * Clé dans la réponse JSON contenant l'entité
   * (par exemple "experience", "training", "user").
   */
  resultKey: keyof TResult;
  onSubmitSuccess?: (entity: TResult[keyof TResult]) => void;
  /**
   * Données supplémentaires à ajouter dans le body (ex: _method: "PUT").
   */
  extraBody?: Record<string, unknown>;
};

export function useEntityForm<TInput, TResult>({
  schema,
  action,
  csrfToken,
  method = "POST",
  initialValues,
  resultKey,
  onSubmitSuccess,
  extraBody,
}: UseEntityFormOptions<TInput, TResult>): UseFormReturn<TInput> & {
  onSubmit: (data: TInput) => Promise<void>;
} {
  const form = useForm<TInput>({
    resolver: zodResolver(schema),
    defaultValues: initialValues,
  });

  const onSubmit = async (data: TInput) => {
    if (!csrfToken) {
      console.error("Token CSRF non disponible");
      return;
    }

    try {
      const response = await fetch(action, {
        method,
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          ...data,
          ...(extraBody ?? {}),
          _csrf_token: csrfToken,
        }),
      });

      const result = await response.json();
      if (!response.ok) {
        throw new Error(result.message || "Erreur lors de la mise à jour");
      }

      const entity = result[resultKey];
      if (entity && onSubmitSuccess) {
        onSubmitSuccess(entity as TResult[keyof TResult]);
      }
    } catch (error) {
      console.error(error);
    }
  };

  return {
    ...form,
    onSubmit,
  };
}

