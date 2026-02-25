import { FieldErrors, UseFormRegister } from "react-hook-form";

type DateRangeFieldsProps<TFieldValues> = {
  register: UseFormRegister<TFieldValues>;
  errors: FieldErrors<TFieldValues>;
};

export function DateRangeFields<TFieldValues>({
  register,
  errors,
}: DateRangeFieldsProps<TFieldValues>) {
  return (
    <>
      <div className="flex flex-col">
        <label htmlFor="start_date" className="mb-1">
          Date de début
        </label>
        <input
          id="start_date"
          type="date"
          {...register("start_date" as any)}
          className="border border-gray-300 rounded px-3 py-2 bg-white"
        />
        {errors.start_date && (
          <p className="text-sm text-red-600 mt-1">
            {(errors.start_date as any).message}
          </p>
        )}
      </div>

      <div className="flex flex-col">
        <label htmlFor="end_date" className="mb-1">
          Date de fin (optionnel)
        </label>
        <input
          id="end_date"
          type="date"
          {...register("end_date" as any)}
          className="border border-gray-300 rounded px-3 py-2 bg-white"
        />
        {errors.end_date && (
          <p className="text-sm text-red-600 mt-1">
            {(errors.end_date as any).message}
          </p>
        )}
      </div>
    </>
  );
}

