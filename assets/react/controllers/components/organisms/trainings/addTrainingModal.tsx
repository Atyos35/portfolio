import React from "react";
import Modal from "../../molecules/modal/modal";
import TrainingForm from "../../atoms/trainings/trainingForm";
import { emptyTraining } from "../../../models/trainings/training.model";

interface AddTrainingModalProps {
  isOpen: boolean;
  onClose: () => void;
  csrfToken: string;
  onAdd: (values: any) => void;
}

export default function AddTrainingModal({
  isOpen,
  onClose,
  csrfToken,
  onAdd,
}: AddTrainingModalProps) {
  return (
    <Modal isOpen={isOpen} onClose={onClose} title="Ajouter une formation">
      <TrainingForm
        initialValues={emptyTraining}
        action="/training/new"
        csrfToken={csrfToken}
        onSubmit={(values) => {
          onAdd(values);
          onClose();
        }}
      />
    </Modal>
  );
}
