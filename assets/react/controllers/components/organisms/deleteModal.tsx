import React from 'react';
import Modal from '../molecules/modal/modal';

interface DeleteModalProps {
  isOpen: boolean;
  onConfirm: () => void;
  onClose: () => void;
}

const DeleteModal: React.FC<DeleteModalProps> = ({ isOpen, onConfirm, onClose }) => {

  const handleConfirm = async () => {
    await onConfirm();
    onClose();
  };

  return (
    <Modal isOpen={isOpen} onClose={onClose} title="Confirmer la suppression">
      <div className="space-y-4">
        <div className="flex justify-center">
          <button
            onClick={handleConfirm}
            className="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
          >
            Supprimer
          </button>
        </div>
      </div>
    </Modal>
  );
};

export default DeleteModal;