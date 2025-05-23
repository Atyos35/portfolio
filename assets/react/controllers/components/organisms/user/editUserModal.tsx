import React from "react";
import Modal from "../../molecules/modal/modal";
import UserForm from "../../atoms/user/userForm";
import { User } from '../../../models/user/user.model';

interface EditUserModalProps {
  isOpen: boolean;
  onClose: () => void;
  csrfToken: string;
  initialValues: User;
  onEdit: (values: User) => void;
}

export default function EditUserModal({
  isOpen,
  onClose,
  csrfToken,
  initialValues,
  onEdit,
}: EditUserModalProps) {
  return (
    <Modal isOpen={isOpen} onClose={onClose} title="Modifier vos informations">
      <UserForm
        initialValues={initialValues}
        action={`/user/${initialValues.id}/edit`}
        csrfToken={csrfToken}
        onSubmit={(values) => {
          onEdit(values);
          onClose();
        }}
      />
    </Modal>
  );
}