import React from 'react';
import { User } from '../../../models/user/user.model';
import EditButton from '../../atoms/editButton';

interface UserItemProps {
  user: User;
  onEdit: (values: any) => void;
};

const UserItem: React.FC<UserItemProps> = ({ user, onEdit }) => {
    console.log('onEdit is:', typeof onEdit);

  return (
    <div className="p-4 border rounded-2xl border-gray-300 shadow-sm flex justify-between items-center">
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
        <EditButton onClick={() => onEdit(user)} />
      </div>
    </div>
  );
};

export default UserItem;