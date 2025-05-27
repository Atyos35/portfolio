import React from 'react';
import { useExperienceForm } from './useExperienceForm';
import { Experience } from '../../../models/experiences/experience.model';
import SaveButton from '../saveButton';

type ExperienceFormProps = {
  initialValues: Experience;
  action: string;
  csrfToken: string;
  onSubmitSuccess?: (values: Experience) => void;
};

export default function ExperienceForm({
  initialValues,
  action,
  csrfToken,
  onSubmitSuccess,
}: ExperienceFormProps) {
  const {
    register,
    handleSubmit,
    onSubmit: submitForm,
    errors,
    isSubmitting,
  } = useExperienceForm(action, csrfToken, initialValues, onSubmitSuccess);

  return (
    <form
      onSubmit={handleSubmit(submitForm)}
      className="space-y-4 bg-transparent text-gray-900 p-4 rounded-lg"
    >
      <input type="hidden" name="_token" value={csrfToken} />

      <div className="flex flex-col">
        <label htmlFor="name" className="mb-1">Nom de l'expérience</label>
        <input
          id="name"
          {...register('name')}
          className="border border-gray-300 rounded px-3 py-2 bg-white"
        />
        {errors.name && <p className="text-sm text-red-600 mt-1">{errors.name.message}</p>}
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
          {...register('company')}
          className="border border-gray-300 rounded px-3 py-2 bg-white"
        />
        {errors.company && <p className="text-sm text-red-600 mt-1">{errors.company.message}</p>}
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

      <SaveButton isSubmitting={isSubmitting} />
    </form>
  );
}