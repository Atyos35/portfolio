import React from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { User } from '../../../models/user/user.model';
import EditButton from '../../atoms/editButton';
import CheckIcon from '../../atoms/checkIcon';

interface UserItemProps {
  user: User;
  onEdit: (values: any) => void;
  showCheckIcon?: boolean;
  flash?: boolean;
}

const UserItem: React.FC<UserItemProps> = ({ user, onEdit, showCheckIcon, flash }) => {
  return (
    <motion.div
      initial={false}
      animate={{ backgroundColor: flash ? '#E6F4EA' : 'rgba(230, 244, 234, 0)' }}
      transition={{ duration: 0.5 }}
      className="p-4 border rounded-2xl border-gray-300 shadow-sm flex justify-between items-center"
    >
      <div>
        <div className="flex items-center space-x-4 mb-2">
          <img
            className="w-20 h-20 rounded-full shadow"
            src="/images/pp.jpg"
            alt="Profile"
          />
          <div>
            <h3 className="text-lg font-semibold">
              {user.firstname} {user.lastname}
            </h3>
            <p className="text-sm text-gray-500">
              {user.job} &bull; {user.linkedin}
            </p>
          </div>
        </div>
        <div className="text-sm text-gray-700 space-y-1">
          <p><strong>Email:</strong> {user.email}</p>
          <p><strong>Ville:</strong> {user.city}</p>
          <p><strong>Age:</strong> {user.age} ans</p>
          <p><strong>Tel:</strong> {user.phone}</p>
        </div>
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
              <EditButton onClick={() => onEdit(user)} />
            </motion.div>
          )}
        </AnimatePresence>
      </div>
    </motion.div>
  );
};

export default UserItem;