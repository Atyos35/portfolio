import React from "react";
import Modal from "../../molecules/modal/modal";
import ExperienceForm from "../../atoms/experiences/experienceForm";
import { emptyExperience, Experience } from "../../../models/experiences/experience.model";

interface AddExperienceModalProps {
  isOpen: boolean;
  onClose: () => void;
  csrfToken: string;
  onAdd: (values: Experience) => void;
}

export default function AddExperienceModal({
  isOpen,
  onClose,
  csrfToken,
  onAdd,
}: AddExperienceModalProps) {
  return (
    <Modal isOpen={isOpen} onClose={onClose} title="Ajouter une expérience">
      <ExperienceForm
        initialValues={emptyExperience}
        action="/experience/new"
        csrfToken={csrfToken}
        onSubmitSuccess={(values) => {
          onAdd(values);
          onClose();
        }}
      />
    </Modal>
  );
}
