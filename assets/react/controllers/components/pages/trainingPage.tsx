import React, { useState } from 'react';
import TrainingList from '../organisms/trainings/trainingList';
import { Training } from '../../models/trainings/training.model';
import AddTrainingModal from '../../components/organisms/trainings/addTrainingModal';
import AddButton from '../../components/atoms/addButton';
import EditTrainingModal from '../../components/organisms/trainings/editTrainingModal';

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

  const handleEditClick = (training: Training) => {
      setTrainingToEdit(training);
      setIsEditModalOpen(true);
    };

  const handleEditTraining = (updatedTraining: Training) => {
      setTrainings((prevTrainings) =>
        sortByDateDesc(
          prevTrainings.map((training) =>
            training.id === updatedTraining.id ? updatedTraining : training
          )
        )
      );
    };

  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [trainingToEdit, setTrainingToEdit] = useState<Training | null>(null);

  const cancel = () => {
    setIsAddModalOpen(false);
    setIsEditModalOpen(false);
  };

  return (
    <div className="p-8">
      <h1 className="text-2xl font-bold mb-6">
        Mes formations
        <AddButton onClick={() => setIsAddModalOpen(true)} />
      </h1>
      <TrainingList
        trainings={trainings}
        onEdit={handleEditClick}
      />
      {isAddModalOpen && (
        <AddTrainingModal
          isOpen={isAddModalOpen}
          onClose={cancel}
          csrfToken={csrfToken}
          onAdd={handleAddTraining}
        />
      )}
      {isEditModalOpen && trainingToEdit && (
        <EditTrainingModal
          isOpen={isEditModalOpen}
          onClose={cancel}
          csrfToken={csrfToken}
          initialValues={{
            id: trainingToEdit.id,
            name: trainingToEdit.name,
            start_date: trainingToEdit.start_date,
            end_date: trainingToEdit.end_date,
            school: trainingToEdit.school,
            city: trainingToEdit.city,
            description: trainingToEdit.description
          }}
          onEdit={handleEditTraining}
        />
      )}
    </div>
  );
};

export default TrainingPage;