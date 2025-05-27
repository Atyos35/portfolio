import { useForm, useFieldArray } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import * as Turbo from "@hotwired/turbo";
import { Block } from "../../../models/blocks/block.model";

const schema = z.object({
  id: z.number().optional(),
  title: z.string(),
  names: z.array(z.object({ value: z.string().min(1) }))
  .min(1, { message: "Au moins un nom est requis." }),
});

export type BlockFormInput = z.infer<typeof schema>;

export function useBlockForm(
  action: string,
  csrfToken: string,
  initialValues: BlockFormInput = {
    title: "",
    names: [{ value: "" }],
  },
  onSubmitSuccess?: (data: Block) => void
) {
  const {
    register,
    handleSubmit,
    control,
    formState: { errors, isSubmitting },
    reset,
  } = useForm<BlockFormInput>({
    resolver: zodResolver(schema),
    defaultValues: {
      ...initialValues,
      names: initialValues.names?.length? initialValues.names: [{ value: "" }],
    },
  });

  const { fields, append, remove } = useFieldArray({
    name: 'names',
    control,
  });

  const onSubmit = async (data: BlockFormInput) => {
    try {
      const response = await fetch(action, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          ...data,
          _csrf_token: csrfToken,
        }),
      });

      const result = await response.json();
            if (!response.ok) throw new Error("Erreur lors de la mise à jour");

        onSubmitSuccess?.(result.block);
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
    fields,
    append,
    remove,
  };
}