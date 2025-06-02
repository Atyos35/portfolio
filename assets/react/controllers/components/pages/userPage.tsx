import React, { useState } from 'react';
import { User } from '../../models/user/user.model';
import UserItem from '../organisms/user/userItem';
import EditUserModal from '../organisms/user/editUserModal';

interface UserPageProps {
  user: User;
  csrfToken: string;
  editUserAction: string;
  onUserUpdate: (user: User) => void;
}

const UserPage: React.FC<UserPageProps> = ({
  user,
  csrfToken,
  editUserAction,
  onUserUpdate,
}) => {
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [localUser, setLocalUser] = useState(user);
  const [isFlashSuccess, setIsFlashSuccess] = useState(false);
  const [showCheckIcon, setShowCheckIcon] = useState(false);

  const handleEditClick = () => {
    setIsEditModalOpen(true);
  };

  const handleEditUser = (updatedUser: User) => {
    setLocalUser(updatedUser);
    onUserUpdate(updatedUser);
    setIsEditModalOpen(false);

    setIsFlashSuccess(true);
    setShowCheckIcon(true);
    setTimeout(() => setIsFlashSuccess(false), 1000);
    setTimeout(() => setShowCheckIcon(false), 2000);
  };

  const cancel = () => {
    setIsEditModalOpen(false);
  };

  return (
    <div className="p-8">
      <UserItem
        user={localUser}
        onEdit={handleEditClick}
        onProfilePictureUpdated={handleEditUser}
        showCheckIcon={showCheckIcon}
        flash={isFlashSuccess}
        csrfToken={csrfToken}
      />

      {isEditModalOpen && (
        <EditUserModal
          isOpen={isEditModalOpen}
          onClose={cancel}
          csrfToken={csrfToken}
          initialValues={localUser}
          onEdit={handleEditUser}
          editUserAction={editUserAction}
        />
      )}
    </div>
  );
};

export default UserPage;