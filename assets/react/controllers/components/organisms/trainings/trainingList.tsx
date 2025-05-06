import React from 'react';
import { Training } from '../../../models/trainings/training.model';
import TrainingItem from './trainingItem';

interface TrainingListProps {
  trainings: Training[];
  onEdit: (values: any) => void;
}

const TrainingList: React.FC<TrainingListProps> = ({
  trainings,
  onEdit,
}) => {
  return (
    <div className="space-y-4">
      {trainings.map((training) => (
        <TrainingItem
          key={training.id}
          training={training}
          onEdit={onEdit}
        />
      ))}
    </div>
  );
};

export default TrainingList;