import React from 'react';
import { Experience } from '../../../models/experiences/experience.model';
import ExperienceItem from '../experiences/experienceItem';

interface ExperienceListProps {
  experiences: Experience[];
  onDeleted: (id: number) => void;
  onEdit: (experience: Experience) => void;
  editedExperienceId?: number | null;
  flashSuccessId?: number | null;
}

const ExperienceList: React.FC<ExperienceListProps> = ({
  experiences,
  onDeleted,
  onEdit,
  editedExperienceId,
  flashSuccessId
}) => {
  return (
    <div className="space-y-4">
      {experiences.map((experience) => (
        <ExperienceItem
          key={experience.id}
          experience={experience}
          onDelete={onDeleted}
          onEdit={onEdit}
          showCheckIcon={editedExperienceId === experience.id}
          flash={flashSuccessId === experience.id}
        />
      ))}
    </div>
  );
};

export default ExperienceList;