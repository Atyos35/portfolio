import React from 'react';
import { useUserForm } from './useUserForm';
import { User } from '../../../models/user/user.model';
import { UserFormInput } from './useUserForm';

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
    <form onSubmit={handleSubmit(submitForm)} className="space-y-4">
      <input type="hidden" name="_token" value={csrfToken} />

      <h2>Informations personnelles</h2>

      <div>
        <label htmlFor="firstname">Prénom</label>
        <input id="firstname" {...register("firstname")} />
        {errors.firstname && <p>{errors.firstname.message}</p>}
      </div>

      <div>
        <label htmlFor="lastname">Nom</label>
        <input id="lastname" {...register("lastname")} />
        {errors.lastname && <p>{errors.lastname.message}</p>}
      </div>

      <div>
        <label htmlFor="job">Métier</label>
        <input id="job" {...register("job")} />
        {errors.job && <p>{errors.job.message}</p>}
      </div>

      <div>
        <label htmlFor="linkedin">LinkedIn</label>
        <input id="linkedin" {...register("linkedin")} />
        {errors.linkedin && <p>{errors.linkedin.message}</p>}
      </div>

      <div>
        <label htmlFor="age">Âge</label>
        <input id="age" type="number" {...register("age")} />
        {errors.age && <p>{errors.age.message}</p>}
      </div>

      <div>
        <label htmlFor="city">Ville</label>
        <input id="city" {...register("city")} />
        {errors.city && <p>{errors.city.message}</p>}
      </div>

      <div>
        <label htmlFor="phone">Téléphone</label>
        <input id="phone" {...register("phone")} />
        {errors.phone && <p>{errors.phone.message}</p>}
      </div>

      <button type="submit" disabled={isSubmitting}>
        {isSubmitting ? "Enregistrement..." : "Enregistrer"}
      </button>
    </form>
  );
}