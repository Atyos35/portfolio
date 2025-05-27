import React, { useState } from 'react';
import TrainingList from '../organisms/trainings/trainingList';
import { Training } from '../../models/trainings/training.model';
import AddTrainingModal from '../../components/organisms/trainings/addTrainingModal';
import AddButton from '../../components/atoms/addButton';
import EditTrainingModal from '../../components/organisms/trainings/editTrainingModal';
import { deleteEntity } from '../../utils/deleteEntity';
import DeleteModal from '../organisms/deleteModal';

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

  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [trainingToEdit, setTrainingToEdit] = useState<Training | null>(null);
  const [trainingToDelete, setTrainingToDelete] = useState<number | null>(null);
  const [editedTrainingId, setEditedTrainingId] = useState<number | null>(null);
  const [flashSuccessId, setFlashSuccessId] = useState<number | null>(null);

  const handleDeleteClick = (id: number) => {
    setTrainingToDelete(id);
    setIsModalOpen(true);
  };

  const confirmDelete = async () => {
    if (trainingToDelete !== null) {
      try {
        await deleteEntity({
          action: `/training/${trainingToDelete}`,
          csrfToken,
          entityName: "la formation",
        });
        setTrainings((prev) => prev.filter((training) => training.id !== trainingToDelete));
        setIsModalOpen(false);
        setTrainingToDelete(null);
      } catch (error: any) {
        console.error(error.message);
        alert("Erreur lors de la suppression : " + error.message);
      }
    }
    setIsModalOpen(false);
    setTrainingToDelete(null);
  };

  const cancelDelete = () => {
    setIsModalOpen(false);
    setTrainingToDelete(null);
  };

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
      setIsEditModalOpen(false);
      setTrainingToEdit(null);

      setEditedTrainingId(updatedTraining.id);
      setFlashSuccessId(updatedTraining.id);
      setTimeout(() => setFlashSuccessId(null), 1000);
      setTimeout(() => setEditedTrainingId(null), 2000);
    };

  const cancel = () => {
    setIsAddModalOpen(false);
    setIsEditModalOpen(false);
    setTrainingToEdit(null);
  };

  return (
    <div className="p-8">
      <div className="flex items-center mb-6">
        <h1 className="text-2xl font-bold">Mes formations</h1>
        <AddButton onClick={() => setIsAddModalOpen(true)} />
      </div>
      <TrainingList
        trainings={trainings}
        onDeleted={handleDeleteClick}
        onEdit={handleEditClick}
        editedTrainingId={editedTrainingId}
        flashSuccessId={flashSuccessId}
      />
      {isAddModalOpen && (
        <AddTrainingModal
          isOpen={isAddModalOpen}
          onClose={cancel}
          csrfToken={csrfToken}
          onAdd={handleAddTraining}
        />
      )}
      <DeleteModal
        isOpen={isModalOpen}
        onConfirm={confirmDelete}
        onClose={cancelDelete}
      />
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