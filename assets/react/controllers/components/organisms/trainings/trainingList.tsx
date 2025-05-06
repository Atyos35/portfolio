import React from 'react';
import { Training } from '../../../models/trainings/training.model';
import TrainingItem from './trainingItem';

interface TrainingListProps {
  trainings: Training[];
  csrfToken: string;
}

const TrainingList: React.FC<TrainingListProps> = ({
  trainings,
  csrfToken,
}) => {
  return (
    <div className="space-y-4">
      {trainings.map((training) => (
        <TrainingItem
          key={training.id}
          training={training}
          csrfToken={csrfToken}
        />
      ))}
    </div>
  );
};

export default TrainingList;