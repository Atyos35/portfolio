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
    <Modal isOpen={isOpen} onClose={onClose} title="Modifier un block">
      <BlockForm
        initialValues={initialValues}
        action={`/block/${initialValues.id}/edit`}
        csrfToken={csrfToken}
        onSubmitSuccess={(updatedBlock) => {
          onEdit(updatedBlock);
          onClose();
        }}
      />
    </Modal>
  );
}