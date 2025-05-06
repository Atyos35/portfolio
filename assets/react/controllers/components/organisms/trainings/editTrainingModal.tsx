import React from "react";
import Modal from "../../molecules/modal/modal";
import TrainingForm from "../../atoms/trainings/trainingForm";
import { Training } from '../../../models/trainings/training.model';

interface EditTrainingModalProps {
  isOpen: boolean;
  onClose: () => void;
  csrfToken: string;
  initialValues: Training;
  onEdit: (values: Training) => void;
}

export default function EditTrainingModal({
  isOpen,
  onClose,
  csrfToken,
  initialValues,
  onEdit,
}: EditTrainingModalProps) {
  return (
    <Modal isOpen={isOpen} onClose={onClose}>
      <h2 className="text-xl font-bold mb-4">Modifier une expérience</h2>
      <TrainingForm
        initialValues={initialValues}
        action={`/training/${initialValues.id}/edit`}
        csrfToken={csrfToken}
        onSubmit={(values) => {
          onEdit(values);
          onClose();
        }}
      />
    </Modal>
  );
}