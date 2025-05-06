import React from 'react';
import { Experience } from '../../../models/experiences/experience.model';
import { formatDuration } from '../../../utils/formatDuration';
import DeleteButton from '../../atoms/deleteButton';
import EditButton from '../../atoms/editButton';

interface ExperienceItemProps {
  experience: Experience;
  onEdit: (experience: Experience) => void;
  onDelete: (id: number) => void;
}

const ExperienceItem: React.FC<ExperienceItemProps> = ({
  experience,
  onEdit,
  onDelete,
}) => {
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
        <EditButton onClick={() => onEdit(experience)} />
        <DeleteButton onClick={() => onDelete(experience.id)} />
      </div>
    </div>
  );
};

export default ExperienceItem;