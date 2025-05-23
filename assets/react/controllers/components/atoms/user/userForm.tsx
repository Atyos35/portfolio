import React from 'react';
import { useUserForm } from './useUserForm';
import { User } from '../../../models/user/user.model';
import { UserFormInput } from './useUserForm';
import SaveButton from '../saveButton';

type UserFormProps = {
  initialValues: UserFormInput;
  action: string;
  csrfToken: string;
  onSubmit?: (values: User) => void;
};

export default function UserForm({
  action,
  csrfToken,
  initialValues,
  onSubmit,
}: UserFormProps) {
  const {
    register,
    handleSubmit,
    onSubmit: submitForm,
    errors,
    isSubmitting,
  } = useUserForm(action, csrfToken, initialValues, (data: User) => {
    if (onSubmit) {
      onSubmit(data);
    }
  });

  return (
    <form
      onSubmit={handleSubmit(submitForm)}
      className="space-y-4 bg-transparent text-gray-900 p-4 rounded-lg"
    >
      <input type="hidden" name="_token" value={csrfToken} />

      <div className="flex flex-col">
        <label htmlFor="firstname" className="mb-1">Prénom</label>
        <input
          id="firstname"
          {...register("firstname")}
          className="border border-gray-300 rounded px-3 py-2 bg-white text-gray-900"
        />
        {errors.firstname && (
          <p className="text-sm text-red-600 mt-1">{errors.firstname.message}</p>
        )}
      </div>

      <div className="flex flex-col">
        <label htmlFor="lastname" className="mb-1">Nom</label>
        <input
          id="lastname"
          {...register("lastname")}
          className="border border-gray-300 rounded px-3 py-2 bg-white text-gray-900"
        />
        {errors.lastname && (
          <p className="text-sm text-red-600 mt-1">{errors.lastname.message}</p>
        )}
      </div>

      <div className="flex flex-col">
        <label htmlFor="job" className="mb-1">Métier</label>
        <input
          id="job"
          {...register("job")}
          className="border border-gray-300 rounded px-3 py-2 bg-white text-gray-900"
        />
        {errors.job && (
          <p className="text-sm text-red-600 mt-1">{errors.job.message}</p>
        )}
      </div>

      <div className="flex flex-col">
        <label htmlFor="linkedin" className="mb-1">LinkedIn</label>
        <input
          id="linkedin"
          {...register("linkedin")}
          className="border border-gray-300 rounded px-3 py-2 bg-white text-gray-900"
        />
        {errors.linkedin && (
          <p className="text-sm text-red-600 mt-1">{errors.linkedin.message}</p>
        )}
      </div>

      <div className="flex flex-col">
        <label htmlFor="age" className="mb-1">Âge</label>
        <input
          id="age"
          type="number"
          {...register("age")}
          className="border border-gray-300 rounded px-3 py-2 bg-white text-gray-900"
        />
        {errors.age && (
          <p className="text-sm text-red-600 mt-1">{errors.age.message}</p>
        )}
      </div>

      <div className="flex flex-col">
        <label htmlFor="city" className="mb-1">Ville</label>
        <input
          id="city"
          {...register("city")}
          className="border border-gray-300 rounded px-3 py-2 bg-white text-gray-900"
        />
        {errors.city && (
          <p className="text-sm text-red-600 mt-1">{errors.city.message}</p>
        )}
      </div>

      <div className="flex flex-col">
        <label htmlFor="phone" className="mb-1">Téléphone</label>
        <input
          id="phone"
          {...register("phone")}
          className="border border-gray-300 rounded px-3 py-2 bg-white text-gray-900"
        />
        {errors.phone && (
          <p className="text-sm text-red-600 mt-1">{errors.phone.message}</p>
        )}
      </div>

      <SaveButton isSubmitting={isSubmitting} />
    </form>
  );
}