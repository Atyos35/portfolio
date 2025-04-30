import React from "react";
import Modal from "../../molecules/modal/modal";
import ExperienceForm from "../../atoms/experiences/experienceForm";
import { emptyExperience } from "../../../models/experiences/experience.model";

interface AddExperienceModalProps {
  isOpen: boolean;
  onClose: () => void;
  csrfToken: string;
  onAdd: (values: any) => void;
}

export default function AddExperienceModal({
  isOpen,
  onClose,
  csrfToken,
  onAdd,
}: AddExperienceModalProps) {
  return (
    <Modal isOpen={isOpen} onClose={onClose}>
      <h2 className="text-xl font-bold mb-4">Ajouter une expérience</h2>
      <ExperienceForm
        initialValues={emptyExperience}
        action="/experience/new"
        csrfToken={csrfToken}
        onSubmit={(values) => {
          onAdd(values);
          onClose();
        }}
      />
    </Modal>
  );
}
