import React from 'react';
import { Training } from '../../../models/trainings/training.model';
import TrainingItem from './trainingItem';

interface TrainingListProps {
  trainings: Training[];
  onEdit: (values: any) => void;
  onDeleted: (id: number) => void;
  editedTrainingId?: number | null;
  flashSuccessId?: number | null;
}

const TrainingList: React.FC<TrainingListProps> = ({
  trainings,
  onEdit,
  onDeleted,
  editedTrainingId,
  flashSuccessId
}) => {
  return (
    <div className="space-y-4">
      {trainings.map((training) => (
        <TrainingItem
          key={training.id}
          training={training}
          onEdit={onEdit}
          onDelete={onDeleted}
          showCheckIcon={editedTrainingId === training.id}
          flash={flashSuccessId === training.id}
        />
      ))}
    </div>
  );
};

export default TrainingList;