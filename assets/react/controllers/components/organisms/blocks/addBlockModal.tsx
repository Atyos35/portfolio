import React from "react";
import Modal from "../../molecules/modal/modal";
import BlockForm from "../../atoms/blocks/blockForm";
import { emptyBlock } from "../../../models/blocks/block.model";

interface AddBlockModalProps {
  isOpen: boolean;
  onClose: () => void;
  csrfToken: string;
  onAdd: (values: any) => void;
}

export default function AddBlockModal({
  isOpen,
  onClose,
  csrfToken,
  onAdd,
}: AddBlockModalProps) {
  return (
    <Modal isOpen={isOpen} onClose={onClose} title="Ajouter un block">
      <BlockForm
        initialValues={emptyBlock}
        action="/block/new"
        csrfToken={csrfToken}
        onSubmitSuccess={(values) => {
          onAdd(values);
          onClose();
        }}
      />
    </Modal>
  );
}
