import React from 'react';
import { Experience } from '../../../models/experiences/experience.model';
import ExperienceItem from './experienceItem';

interface ExperienceListProps {
  experiences: Experience[];
  onDeleted: (id: number) => void;
  onEdit: (experience: Experience) => void;
}

const ExperienceList: React.FC<ExperienceListProps> = ({
  experiences,
  onDeleted,
  onEdit,
}) => {
  return (
    <div className="space-y-4">
      {experiences.map((experience) => (
        <ExperienceItem
          key={experience.id}
          experience={experience}
          onDelete={onDeleted}
          onEdit={onEdit}
        />
      ))}
    </div>
  );
};

export default ExperienceList;