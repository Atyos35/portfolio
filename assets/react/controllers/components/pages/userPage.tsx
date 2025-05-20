import React, { useState } from 'react';
import { User } from '../../models/user/user.model';
import UserItem from '../organisms/user/userItem';
import EditUserModal from '../organisms/user/editUserModal';

interface UserPageProps {
  user: User;
  csrfToken: string;
  editUserAction: string;
}

const UserPage: React.FC<UserPageProps> = ({ user: initialUser, csrfToken }) => {
  const [user, setUser] = useState<User>(initialUser);
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);

  const handleEditClick = (user: User) => {
    setUser(user);
    setIsEditModalOpen(true);
  };

  const handleEditUser = (updatedUser: User) => {
    setUser(updatedUser);
    setIsEditModalOpen(false);
  };

  const cancel = () => {
    setIsEditModalOpen(false);
  };

  return (
    <div className="p-8">
      <h1 className="text-2xl font-bold mb-6">Profil utilisateur</h1>
      <UserItem user={user} onEdit={handleEditClick} />

      {isEditModalOpen && (
        <EditUserModal
          isOpen={isEditModalOpen}
          onClose={cancel}
          csrfToken={csrfToken}
          initialValues={user}
          onEdit={handleEditUser}
        />
      )}
    </div>
  );
};

export default UserPage;