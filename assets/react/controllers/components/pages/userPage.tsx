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

  const handleEditClick = () => {
    setIsEditModalOpen(true);
  };

  const handleEditUser = (updatedUser: User) => {
    onUserUpdate(updatedUser);
    setIsEditModalOpen(false);
  };

  const cancel = () => {
    setIsEditModalOpen(false);
  };

  return (
    <div className="p-8">
      <UserItem user={user} onEdit={handleEditClick} />

      {isEditModalOpen && (
        <EditUserModal
          isOpen={isEditModalOpen}
          onClose={cancel}
          csrfToken={csrfToken}
          initialValues={user}
          onEdit={handleEditUser}
          editUserAction={editUserAction}
        />
      )}
    </div>
  );
};

export default UserPage;