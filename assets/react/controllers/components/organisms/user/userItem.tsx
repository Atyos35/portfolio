import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { User } from '../../../models/user/user.model';
import EditButton from '../../atoms/editButton';
import CheckIcon from '../../atoms/checkIcon';
import EditIcon from '../../atoms/editIcon';
import ProfilePictureDefault from '../../atoms/profilePictureDefault';
import UpdateProfilePictureModal from './updateProfilePictureModal';

interface UserItemProps {
  user: User;
  onEdit: (values: any) => void;
  onProfilePictureUpdated: (user: User) => void;
  showCheckIcon?: boolean;
  flash?: boolean;
  csrfToken: string;
}

const UserItem: React.FC<UserItemProps> = ({
  user,
  onEdit,
  onProfilePictureUpdated,
  showCheckIcon,
  flash,
  csrfToken
}) => {
  const [isModalOpen, setIsModalOpen] = useState(false);

  const handleUpload = async (file: File, csrfToken: string) => {
    const formData = new FormData();
    formData.append('profilePicture', file);
    formData.append('_csrf_token', csrfToken);

    const response = await fetch(`/user/${user.id}/edit/profile_picture`, {
      method: 'POST',
      body: formData,
    });

    if (response.ok) {
      const result = await response.json();
      onProfilePictureUpdated({ ...user, profilePicture: result.profilePicture });
    } else {
      console.error(await response.json());
    }
  };

  return (
    <>
      <motion.div
        initial={false}
        animate={{ backgroundColor: flash ? '#E6F4EA' : 'transparent' }}
        transition={{ duration: 0.5 }}
        className="p-4 border rounded-2xl border-gray-300 shadow-sm w-full flex flex-col md:flex-row md:items-start md:justify-between space-y-6 md:space-y-0 md:space-x-6"
      >
        <div className="flex flex-col items-center md:items-start">
          <div className="relative w-24 h-24 mb-2">
            <div
              className="w-full h-full rounded-full shadow overflow-hidden cursor-pointer"
              onClick={() => setIsModalOpen(true)}
              title="Modifier la photo de profil"
            >
              {user.profilePicture ? (
                <img
                  className="w-full h-full object-cover"
                  src={user.profilePicture}
                  alt="Profil"
                />
              ) : (
                <ProfilePictureDefault />
              )}
            </div>
            <div
              className="absolute bottom-0 right-0 bg-white p-1 rounded-full cursor-pointer"
              onClick={() => setIsModalOpen(true)}
              title="Modifier la photo"
            >
              <EditIcon />
            </div>
          </div>
          <div className="text-center md:text-left">
            <h3 className="text-lg font-semibold">
              {user.firstname} {user.lastname}
            </h3>
            <p className="text-sm text-gray-500">{user.job}</p>
            <p className="text-sm text-gray-500 break-all">{user.linkedin}</p>
          </div>
        </div>

        <div className="bg-blue-100/40 rounded-xl p-4 shadow text-sm text-gray-700 w-full md:w-auto space-y-1">
          <p><strong>Email:</strong> {user.email}</p>
          <p><strong>Ville:</strong> {user.city}</p>
          <p><strong>Âge:</strong> {user.age} ans</p>
          <p><strong>Tél:</strong> {user.phone}</p>
        </div>
        
        <div className="flex justify-center md:justify-end w-full md:w-auto">
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

      <UpdateProfilePictureModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onUpload={handleUpload}
        csrfToken={csrfToken}
      />
    </>
  );
};

export default UserItem;