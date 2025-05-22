import React, { useState } from 'react';
import ExperienceList from '../organisms/experiences/experienceList';
import DeleteModal from '../organisms/deleteModal';
import { Experience } from '../../models/experiences/experience.model';
import EditExperienceModal from '../../components/organisms/experiences/editExperienceModal';
import AddExperienceModal from '../../components/organisms/experiences/addExperienceModal';
import AddButton from '../../components/atoms/addButton';
import { deleteEntity } from '../../utils/deleteEntity';

interface ExperiencePageProps {
  experiences: Experience[];
  csrfToken: string;
}

const ExperiencePage: React.FC<ExperiencePageProps> = ({ experiences: initialExperiences, csrfToken }) => {
  const sortByDateDesc = (experiences: Experience[]) => {
    return experiences.sort((a, b) => {
      if (!a.end_date && !b.end_date) return new Date(b.start_date).getTime() - new Date(a.start_date).getTime();
      if (!a.end_date) return -1;
      if (!b.end_date) return 1;
      return new Date(b.end_date).getTime() - new Date(a.end_date).getTime();
    });
  };
  
  const [experiences, setExperiences] = useState<Experience[]>(
    sortByDateDesc(initialExperiences)
  );

  const [isModalOpen, setIsModalOpen] = useState(false);
  const [experienceToDelete, setExperienceToDelete] = useState<number | null>(null);

  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [experienceToEdit, setExperienceToEdit] = useState<Experience | null>(null);

  const handleDeleteClick = (id: number) => {
    setExperienceToDelete(id);
    setIsModalOpen(true);
  };

  const confirmDelete = async () => {
    if (experienceToDelete !== null) {
      try {
        await deleteEntity({
          action: `/experience/${experienceToDelete}`,
          csrfToken,
          entityName: "l’expérience",
        });
        setExperiences((prev) => prev.filter((exp) => exp.id !== experienceToDelete));
      } catch (error: any) {
        console.error(error.message);
        alert("Erreur lors de la suppression : " + error.message);
      }
    }
    setIsModalOpen(false);
    setExperienceToDelete(null);
  };

  const cancelDelete = () => {
    setIsModalOpen(false);
    setExperienceToDelete(null);
  };

  const handleEditClick = (experience: Experience) => {
    setExperienceToEdit(experience);
    setIsEditModalOpen(true);
  };
  
  const handleAddExperience = (experience: Experience) => {
    setExperiences((prevExperiences) =>
      sortByDateDesc([...prevExperiences, experience])
    );
  };
  
  const handleEditExperience = (updatedExperience: Experience) => {
    setExperiences((prevExperiences) =>
      sortByDateDesc(
        prevExperiences.map((exp) =>
          exp.id === updatedExperience.id ? updatedExperience : exp
        )
      )
    );
  };

  const cancel = () => {
    setIsEditModalOpen(false);
    setIsAddModalOpen(false);
    setExperienceToEdit(null);
  };

  return (
    <div className="p-8">
      <div className="flex items-center mb-6">
        <h1 className="text-2xl font-bold">Mes expériences</h1>
        <AddButton onClick={() => setIsAddModalOpen(true)} />
      </div>
      <ExperienceList
        experiences={experiences}
        onDeleted={handleDeleteClick}
        onEdit={handleEditClick}
      />
      {isAddModalOpen && (
        <AddExperienceModal
          isOpen={isAddModalOpen}
          onClose={cancel}
          csrfToken={csrfToken}
          onAdd={handleAddExperience}
        />
      )}
      <DeleteModal
        isOpen={isModalOpen}
        onConfirm={confirmDelete}
        onCancel={cancelDelete}
      />
      {isEditModalOpen && experienceToEdit && (
        <EditExperienceModal
          isOpen={isEditModalOpen}
          onClose={cancel}
          csrfToken={csrfToken}
          initialValues={{
            id: experienceToEdit.id,
            name: experienceToEdit.name,
            start_date: experienceToEdit.start_date,
            end_date: experienceToEdit.end_date,
            company: experienceToEdit.company,
            city: experienceToEdit.city,
            description: experienceToEdit.description
          }}
          onEdit={handleEditExperience}
        />
      )}
    </div>
  );
};

export default ExperiencePage;