import { Training } from '../../../models/trainings/training.model';
import { useTrainingForm } from './useTrainingForm';

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
  } = useTrainingForm(
    action,
    csrfToken,
    initialValues,
    (data: Training) => {
      if (onSubmit) {
        onSubmit(data);
      }
    }
  );

  return (
    <form onSubmit={handleSubmit(submitForm)} className="space-y-4">
      <input type="hidden" name="_token" value={csrfToken} />

      <div>
        <label>Nom de l'expérience</label>
        <input type="text" {...register('name')} required />
        {errors.name && <p>{errors.name.message}</p>}
      </div>

      <div>
        <label>Date de début</label>
        <input type="date" {...register('start_date')} required />
        {errors.start_date && <p>{errors.start_date.message}</p>}
      </div>

      <div>
        <label>Date de fin (optionnel)</label>
        <input type="date" {...register('end_date')} />
        {errors.end_date && <p>{errors.end_date.message}</p>}
      </div>

      <div>
        <label>Description</label>
        <textarea {...register('description')} required />
        {errors.description && <p>{errors.description.message}</p>}
      </div>

      <div>
        <label>École</label>
        <input type="text" {...register('school')} required />
        {errors.school && <p>{errors.school.message}</p>}
      </div>

      <div>
        <label>Ville</label>
        <input type="text" {...register('city')} required />
        {errors.city && <p>{errors.city.message}</p>}
      </div>

      <button type="submit" className="btn btn-primary" disabled={isSubmitting}>
        {isSubmitting ? 'Enregistrement...' : 'Enregistrer'}
      </button>
    </form>
  );
}