import React from 'react';
import { useBlockForm } from './useBlockForm';
import { Block } from '../../../models/blocks/block.model';
import SaveButton from '../saveButton';
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
  } = useBlockForm(action, csrfToken, initialValues, (data: Block) => {
    if (onSubmit) onSubmit(data);
  });

  return (
    <form
      onSubmit={handleSubmit(submitForm)}
      className="space-y-4 bg-transparent text-gray-900 p-4 rounded-lg"
    >
      <input type="hidden" name="_token" value={csrfToken} />

      <div className="flex flex-col">
        <label htmlFor="title" className="mb-1">Titre</label>
        <input
          id="title"
          {...register("title")}
          className="border border-gray-300 rounded px-3 py-2 bg-white text-gray-900"
        />
        {errors.title && <p className="text-sm text-red-600 mt-1">{errors.title.message}</p>}
      </div>

      <div className="space-y-2">
        <label htmlFor="title" className="mb-1">Compétences</label>
        {fields.map((field, index) => (
          <div key={field.id} className="flex items-center gap-2">
            <input
              {...register(`names.${index}.value`)}
              defaultValue={field.value}
              className="border border-gray-300 rounded px-3 py-2 bg-white text-gray-900 flex-1"
            />
            {fields.length > 1 && (
              <DeleteButton onClick={() => remove(index)} />
            )}
          </div>
        ))}
        <AddButton onClick={() => append({ value: "" })} />
      </div>

      <SaveButton isSubmitting={isSubmitting} />
    </form>
  );
}