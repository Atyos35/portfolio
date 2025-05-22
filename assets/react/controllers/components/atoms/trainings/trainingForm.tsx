import React from 'react';
import { useTrainingForm } from './useTrainingForm';
import { Training } from '../../../models/trainings/training.model';
import SaveButton from '../saveButton';

type TrainingFormProps = {
  initialValues: Training;
  action: string;
  csrfToken: string;
  onSubmit?: (values: Training) => void;
};

export default function TrainingForm({
  initialValues,
  action,
  csrfToken,
  onSubmit,
}: TrainingFormProps) {
  const {
    register,
    handleSubmit,
    onSubmit: submitForm,
    errors,
    isSubmitting,
  } = useTrainingForm(action, csrfToken, initialValues, (data: Training) => {
    if (onSubmit) onSubmit(data);
  });

  return (
    <form
      onSubmit={handleSubmit(submitForm)}
      className="space-y-4 bg-transparent text-gray-900 p-4 rounded-lg"
    >
      <input type="hidden" name="_token" value={csrfToken} />

      <div className="flex flex-col">
        <label htmlFor="name" className="mb-1">Nom de la formation</label>
        <input
          id="name"
          {...register('name')}
          className="border border-gray-300 rounded px-3 py-2 bg-white"
        />
        {errors.name && <p className="text-sm text-red-600 mt-1">{errors.name.message}</p>}
      </div>

      <div className="flex flex-col">
        <label htmlFor="description" className="mb-1">Description</label>
        <textarea
          id="description"
          {...register('description')}
          className="border border-gray-300 rounded px-3 py-2 bg-white"
        />
        {errors.description && <p className="text-sm text-red-600 mt-1">{errors.description.message}</p>}
      </div>

      <div className="flex flex-col">
        <label htmlFor="company" className="mb-1">Entreprise</label>
        <input
          id="company"
          {...register('school')}
          className="border border-gray-300 rounded px-3 py-2 bg-white"
        />
        {errors.school && <p className="text-sm text-red-600 mt-1">{errors.school.message}</p>}
      </div>

      <div className="flex flex-col">
        <label htmlFor="city" className="mb-1">Ville</label>
        <input
          id="city"
          {...register('city')}
          className="border border-gray-300 rounded px-3 py-2 bg-white"
        />
        {errors.city && <p className="text-sm text-red-600 mt-1">{errors.city.message}</p>}
      </div>

      <div className="flex flex-col">
        <label htmlFor="start_date" className="mb-1">Date de début</label>
        <input
          id="start_date"
          type="date"
          {...register('start_date')}
          className="border border-gray-300 rounded px-3 py-2 bg-white"
        />
        {errors.start_date && <p className="text-sm text-red-600 mt-1">{errors.start_date.message}</p>}
      </div>

      <div className="flex flex-col">
        <label htmlFor="end_date" className="mb-1">Date de fin (optionnel)</label>
        <input
          id="end_date"
          type="date"
          {...register('end_date')}
          className="border border-gray-300 rounded px-3 py-2 bg-white"
        />
        {errors.end_date && <p className="text-sm text-red-600 mt-1">{errors.end_date.message}</p>}
      </div>

      <SaveButton isSubmitting={isSubmitting} />
    </form>
  );
}