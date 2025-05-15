import React from "react";
import Modal from "../../molecules/modal/modal";
import BlockForm from "../../atoms/blocks/blockForm";
import { Block } from '../../../models/blocks/block.model';

interface EditBlockModalProps {
  isOpen: boolean;
  onClose: () => void;
  csrfToken: string;
  initialValues: Block;
  onEdit: (values: Block) => void;
}

export default function EditBlockModal({
  isOpen,
  onClose,
  csrfToken,
  initialValues,
  onEdit,
}: EditBlockModalProps) {
  return (
    <Modal isOpen={isOpen} onClose={onClose}>
      <h2 className="text-xl font-bold mb-4">Modifier un Block</h2>
      <BlockForm
        initialValues={initialValues}
        action={`/block/${initialValues.id}/edit`}
        csrfToken={csrfToken}
        onSubmit={(values) => {
          onEdit(values);
          onClose();
        }}
      />
    </Modal>
  );
}