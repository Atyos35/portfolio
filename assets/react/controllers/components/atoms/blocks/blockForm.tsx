import { Block } from "../../../models/blocks/block.model";
import { useBlockForm } from "./useBlockForm";
import AddButton from '../../atoms/addButton';
import DeleteButton from '../../atoms/deleteButton';

type BlockFormProps = {
  initialValues: Block;
  action: string;
  csrfToken: string;
  onSubmit?: (values: Block) => void;
};

export default function BlockForm({
  initialValues,
  action,
  csrfToken,
  onSubmit,
}: BlockFormProps) {
  const {
    register,
    handleSubmit,
    onSubmit: submitForm,
    errors,
    isSubmitting,
    fields,
    append,
    remove,
  } = useBlockForm(
    action,
    csrfToken,
    initialValues,
    (data: Block) => {
      if (onSubmit) {
        onSubmit(data);
      }
    }
  );

  return (
    <form onSubmit={handleSubmit(submitForm)} className="space-y-4">
      <input type="hidden" name="_token" value={csrfToken} />

      <div>
        <label>Titre</label>
        <input type="text" {...register("title")} required />
        {errors.title && <p className="text-red-500">{errors.title.message}</p>}
      </div>

      <div>
        {fields.map((field, index) => (
          <div key={field.id}>
            <input
              type="text"
              {...register(`names.${index}.value`)}
              defaultValue={field.value}
              required
            />
            <DeleteButton onClick={() => remove(index)} />
          </div>
        ))}
        <AddButton onClick={() => append({ value: "" })} />
      </div>

      <button type="submit" className="btn btn-primary" disabled={isSubmitting}>
        {isSubmitting ? "Enregistrement..." : "Enregistrer"}
      </button>
    </form>
  );
}