import React from 'react';

type SaveButtonProps = {
  isSubmitting: boolean;
};

export default function SaveButton({ isSubmitting }: SaveButtonProps) {
  return (
    <button
      type="submit"
      disabled={isSubmitting}
      className="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded disabled:opacity-50"
    >
      {isSubmitting ? 'Enregistrement...' : 'Enregistrer'}
    </button>
  );
}