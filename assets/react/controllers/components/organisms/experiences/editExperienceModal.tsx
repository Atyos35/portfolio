import React from "react";
import Modal from "../../molecules/modal/modal";
import ExperienceForm from "../../atoms/experiences/experienceForm";
import { Experience } from '../../../models/experiences/experience.model';

interface EditExperienceModalProps {
  isOpen: boolean;
  onClose: () => void;
  csrfToken: string;
  initialValues: Experience;
  onEdit: (values: Experience) => void;
}

export default function EditExperienceModal({
  isOpen,
  onClose,
  csrfToken,
  initialValues,
  onEdit,
}: EditExperienceModalProps) {
  return (
    <Modal isOpen={isOpen} onClose={onClose} title="Modifier une expérience">
      <ExperienceForm
        initialValues={initialValues}
        action={`/experience/${initialValues.id}/edit`}
        csrfToken={csrfToken}
        onSubmitSuccess={(updatedExperience) => {
          onEdit(updatedExperience);
          onClose();
        }}
      />
    </Modal>
  );
}