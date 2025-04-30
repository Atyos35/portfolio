import React, { useState } from 'react';
import { Experience } from '../../../models/experiences/experience.model';
import { formatDuration } from '../../../utils/formatDuration';
import EditExperienceModal from '../../organisms/experiences/editExperienceModal';
import DeleteButton from '../../atoms/deleteButton';
import EditButton from '../../atoms/editButton';
import { deleteEntity } from '../../../utils/deleteEntity';

interface ExperienceItemProps {
  experience: Experience;
  csrfToken: string;
  onEdit: (values: any) => void;
  onDeleted: (id: number) => void;
}

const ExperienceItem: React.FC<ExperienceItemProps> = ({
  experience,
  csrfToken,
  onEdit,
  onDeleted,
}) => {
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);

  const openEditModal = () => setIsEditModalOpen(true);
  const closeEditModal = () => setIsEditModalOpen(false);

  const handleDelete = async () => {
    try {
      await deleteEntity({
        action: `/experience/${experience.id}`,
        csrfToken,
        entityName: "l’expérience",
      });
      onDeleted(experience.id);
    } catch (error: any) {
      console.error(error.message);
      alert("Erreur lors de la suppression : " + error.message);
    }
  };

  return (
    <div className="p-4 border rounded shadow-sm flex justify-between items-center">
      <div>
        <h3 className="text-lg font-semibold">{experience.name}</h3>
        <p className="text-sm text-gray-600">
          {experience.company} - {experience.city}
        </p>
        <p className="text-xs text-gray-500">
          {experience.start_date} - {experience.end_date}
          {experience.duration && <> ({formatDuration(experience.duration)})</>}
        </p>
      </div>
      <div className="flex space-x-2">
        <EditButton onClick={openEditModal} />
        <DeleteButton onClick={handleDelete} />
      </div>

      {isEditModalOpen && (
        <EditExperienceModal
          isOpen={isEditModalOpen}
          onClose={closeEditModal}
          csrfToken={csrfToken}
          initialValues={{
            id: experience.id,
            name: experience.name,
            start_date: experience.start_date,
            end_date: experience.end_date,
            company: experience.company,
            city: experience.city,
            description: experience.description,
          }}
          onEdit={onEdit}
        />
      )}
    </div>
  );
};

export default ExperienceItem;