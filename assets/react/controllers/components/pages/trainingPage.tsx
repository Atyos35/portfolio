import React, { useState } from 'react';
import TrainingList from '../organisms/trainings/trainingList';
import { Training } from '../../models/trainings/training.model';
import AddTrainingModal from '../../components/organisms/trainings/addTrainingModal';
import AddButton from '../../components/atoms/addButton';

interface TrainingPageProps {
  trainings: Training[];
  csrfToken: string;
}

const TrainingPage: React.FC<TrainingPageProps> = ({ trainings: initialTrainings, csrfToken }) => {
  const sortByDateDesc = (trainings: Training[]) => {
    return trainings.sort((a, b) => {
      if (!a.end_date && !b.end_date) return new Date(b.start_date).getTime() - new Date(a.start_date).getTime();
      if (!a.end_date) return -1;
      if (!b.end_date) return 1;
      return new Date(b.end_date).getTime() - new Date(a.end_date).getTime();
    });
  };
  
  const [trainings, setTrainings] = useState<Training[]>(
    sortByDateDesc(initialTrainings)
  );

  const [isAddModalOpen, setIsAddModalOpen] = useState(false);

  const handleAddTraining = (training: Training) => {
      setTrainings((prevTrainings) =>
        sortByDateDesc([...prevTrainings, training])
      );
    };

  const cancel = () => {
    setIsAddModalOpen(false);
  };

  return (
    <div className="p-8">
      <h1 className="text-2xl font-bold mb-6">
        Mes formations
        <AddButton onClick={() => setIsAddModalOpen(true)} />
      </h1>
      <TrainingList
        trainings={trainings}
        csrfToken={csrfToken}
      />
      {isAddModalOpen && (
        <AddTrainingModal
          isOpen={isAddModalOpen}
          onClose={cancel}
          csrfToken={csrfToken}
          onAdd={handleAddTraining}
        />
      )}
    </div>
  );
};

export default TrainingPage;