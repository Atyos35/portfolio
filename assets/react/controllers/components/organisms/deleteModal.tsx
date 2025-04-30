import React from 'react';
import Modal from '../molecules/modal/modal';

interface DeleteModalProps {
  isOpen: boolean;
  onConfirm: () => void;
  onCancel: () => void;
}

const DeleteModal: React.FC<DeleteModalProps> = ({ isOpen, onConfirm, onCancel }) => {
  return (
    <Modal isOpen={isOpen} onClose={onCancel}>
      <div className="space-y-4">
        <h2 className="text-xl font-bold">Confirmer la suppression</h2>
        <p>Es-tu sûr de vouloir supprimer cette carte ?</p>
        <div className="flex justify-end space-x-4">
          <button
            onClick={onCancel}
            className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition"
          >
            Annuler
          </button>
          <button
            onClick={onConfirm}
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