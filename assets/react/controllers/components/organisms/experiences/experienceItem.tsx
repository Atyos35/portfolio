import React from 'react';
import { Experience } from '../../../models/experiences/experience.model';
import { formatDuration } from '../../../utils/formatDuration';
import DeleteButton from '../../atoms/deleteButton';
import EditButton from '../../atoms/editButton';
import { motion, AnimatePresence } from 'framer-motion';
import CheckIcon from '../../atoms/checkIcon';

interface ExperienceItemProps {
  experience: Experience;
  onEdit: (experience: Experience) => void;
  onDelete: (id: number) => void;
  showCheckIcon?: boolean;
  flash?: boolean;
}

const ExperienceItem: React.FC<ExperienceItemProps> = ({
  experience,
  onEdit,
  onDelete,
  showCheckIcon,
  flash
}) => {
  return (
    <motion.div
      initial={false}
      animate={{ backgroundColor: flash ? '#E6F4EA' : 'rgba(230, 244, 234, 0)' }}
      transition={{ duration: 0.5 }}
      className="p-4 border rounded-2xl border-gray-300 shadow-sm flex justify-between items-center"
    >
      <div>
        <h3 className="text-lg font-semibold">{experience.name}</h3>
        <p className="text-sm text-gray-600">
          {experience.company} - {experience.city}
        </p>
        <p className="text-xs text-gray-500">
          {experience.start_date} - {experience.end_date}
          {experience.duration && <> ({formatDuration(experience.duration)})</>}
        </p>
        <p className="text-sm text-gray-600">
          {experience.description}
        </p>
      </div>
      <div className="flex space-x-2">
        <AnimatePresence mode="wait">
          {showCheckIcon ? (
            <motion.div
              key="check"
              initial={{ opacity: 0, scale: 0.8 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.8 }}
              transition={{ duration: 0.3 }}
              className="rounded-full shadow"
            >
              <CheckIcon />
            </motion.div>
          ) : (
            <motion.div
              key="edit"
              initial={{ opacity: 0, scale: 0.8 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.8 }}
              transition={{ duration: 0.3 }}
            >
              <EditButton onClick={() => onEdit(experience)} />
              <DeleteButton onClick={() => onDelete(experience.id)} />
            </motion.div>
          )}
        </AnimatePresence>
      </div>
    </motion.div>
  );
};

export default ExperienceItem;