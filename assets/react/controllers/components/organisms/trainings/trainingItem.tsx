import React, { useState } from 'react';
import { Training } from '../../../models/trainings/training.model';
import { formatDuration } from '../../../utils/formatDuration';
import EditButton from '../../atoms/editButton';

interface TrainingItemProps {
  training: Training;
  onEdit: (values: any) => void;
}

const TrainingItem: React.FC<TrainingItemProps> = ({
  training,
  onEdit,
}) => {
  return (
    <div className="p-4 border rounded shadow-sm flex justify-between items-center">
      <div>
        <h3 className="text-lg font-semibold">{training.name}</h3>
        <p className="text-sm text-gray-600">
          {training.school} - {training.city}
        </p>
        <p className="text-xs text-gray-500">
          {training.start_date} - {training.end_date}
          {training.duration && <> ({formatDuration(training.duration)})</>}
        </p>
      </div>
      <div className="flex space-x-2">
        <EditButton onClick={() => onEdit(training)} />
      </div>
    </div>
  );
};

export default TrainingItem;