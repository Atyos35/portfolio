import React, { useState } from 'react';
import TrainingList from '../organisms/trainings/trainingList';
import { Training } from '../../models/trainings/training.model';

interface TrainingPageProps {
  trainings: Training[];
  csrfToken: string;
}

const ExperiencePage: React.FC<TrainingPageProps> = ({ trainings: initialTrainings, csrfToken }) => {
  const sortByDateDesc = (trainings: Training[]) => {
    return trainings.sort((a, b) => {
      if (!a.end_date && !b.end_date) return new Date(b.start_date).getTime() - new Date(a.start_date).getTime();
      if (!a.end_date) return -1;
      if (!b.end_date) return 1;
      return new Date(b.end_date).getTime() - new Date(a.end_date).getTime();
    });
  };
  
  const [trainings] = useState<Training[]>(
    sortByDateDesc(initialTrainings)
  );

  return (
    <div className="p-8">
      <h1 className="text-2xl font-bold mb-6">
        Mes formations
      </h1>
      <TrainingList
        trainings={trainings}
        csrfToken={csrfToken}
      />
    </div>
  );
};

export default ExperiencePage;