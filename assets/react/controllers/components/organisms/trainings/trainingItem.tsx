import React, { useState } from 'react';
import { Training } from '../../../models/trainings/training.model';
import { formatDuration } from '../../../utils/formatDuration';
import EditButton from '../../atoms/editButton';
import DeleteButton from '../../atoms/deleteButton';
import { motion, AnimatePresence } from 'framer-motion';
import CheckIcon from '../../atoms/checkIcon';

interface TrainingItemProps {
  training: Training;
  onEdit: (values: any) => void;
  onDelete: (id: number) => void;
  showCheckIcon?: boolean;
  flash?: boolean;
  canDelete: boolean;
}

const TrainingItem: React.FC<TrainingItemProps> = ({
  training,
  onEdit,
  onDelete,
  showCheckIcon,
  flash,
  canDelete
}) => {
  const [expanded, setExpanded] = useState(false);
  return (
    <motion.div
      initial={false}
      animate={{ backgroundColor: flash ? '#E6F4EA' : 'rgba(230, 244, 234, 0)' }}
      transition={{ duration: 0.5 }}
      className="p-4 border rounded-2xl border-gray-300 shadow-sm flex justify-between items-center"
    >
      <div className="max-w-[31.25rem] flex-grow break-words">
        <h3 className="text-lg font-semibold">{training.name}</h3>
        <p className="text-sm text-gray-600">
          {training.school} - {training.city}
        </p>
        <p className="text-xs text-gray-500">
          {training.start_date} - {training.end_date}
          {training.duration && <> ({formatDuration(training.duration)})</>}
        </p>
        <div
          className={`
            experience-description text-sm text-gray-600 max-w-[31.25rem] 
            ${expanded ? "" : "line-clamp-3 overflow-hidden"}
          `}
          dangerouslySetInnerHTML={{ __html: training.description }}
        />
        <button
          className="mt-1 text-blue-600 hover:underline text-sm"
          onClick={() => setExpanded(!expanded)}
          aria-label={expanded ? "Réduire la description" : "Afficher plus"}
        >
          {expanded ? "Voir moins" : "Voir plus"}
        </button>
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
              <EditButton onClick={() => onEdit(training)} />
              {canDelete && (
                <DeleteButton onClick={() => onDelete(training.id)} />
              )}
            </motion.div>
          )}
        </AnimatePresence>
      </div>
    </motion.div>
  );
};

export default TrainingItem;