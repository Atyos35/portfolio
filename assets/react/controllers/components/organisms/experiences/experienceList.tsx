import React from 'react';
import { Experience } from '../../../models/experiences/experience.model';
import ExperienceItem from './experienceItem';

interface ExperienceListProps {
  experiences: Experience[];
  csrfToken: string;
  onDeleted: (id: number) => void;
  onEdit: (values: any) => void;
}

const ExperienceList: React.FC<ExperienceListProps> = ({
  experiences,
  csrfToken,
  onDeleted,
  onEdit,
}) => {
  return (
    <div className="space-y-4">
      {experiences.map((experience) => (
        <ExperienceItem
          key={experience.id}
          experience={experience}
          csrfToken={csrfToken}
          onDeleted={onDeleted}
          onEdit={onEdit}
        />
      ))}
    </div>
  );
};

export default ExperienceList;