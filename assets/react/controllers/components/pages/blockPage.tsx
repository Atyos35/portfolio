import React, { useState } from 'react';
import BlockList from '../organisms/blocks/blockList';
import { Block } from '../../models/blocks/block.model';
import AddButton from '../../components/atoms/addButton';
import AddBlockModal from '../../components/organisms/blocks/addBlockModal';
import EditBlockModal from '../../components/organisms/blocks/editBlockModal';
import { deleteEntity } from '../../utils/deleteEntity';
import DeleteModal from '../organisms/deleteModal';
import Spinner from "../../components/atoms/spinner";
import { AnimatePresence, motion } from "framer-motion";

interface BlockPageProps {
  blocks: Block[];
  csrfToken: string;
}

const BlockPage: React.FC<BlockPageProps> = ({ blocks: initialBlock, csrfToken }) => {

  const [blocks, setBlocks] = useState<Block[]>(initialBlock);
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [blockToEdit, setBlockToEdit] = useState<Block | null>(null);
  const [blockToDelete, setBlockToDelete] = useState<number | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isReordering, setIsReordering] = useState(false);
  const [editedBlockId, setEditedBlockId] = useState<number | null>(null);
  const [flashSuccessId, setFlashSuccessId] = useState<number | null>(null);
  
  const handleDeleteClick = (id: number) => {
    setBlockToDelete(id);
    setIsModalOpen(true);
  };

  const confirmDelete = async () => {
    if (blockToDelete !== null) {
      try {
        await deleteEntity({
          action: `/block/${blockToDelete}`,
          csrfToken,
          entityName: "la formation",
        });
        setBlocks((prev) => prev.filter((block) => block.id !== blockToDelete));
        setIsModalOpen(false);
        setBlockToDelete(null);
      } catch (error: any) {
        console.error(error.message);
        alert("Erreur lors de la suppression : " + error.message);
      }
    }
    setIsModalOpen(false);
    setBlockToDelete(null);
  };

  const cancelDelete = () => {
    setIsModalOpen(false);
    setBlockToDelete(null);
  };

  const handleAddBlock = (block: Block) => {
    setBlocks((prevBlocks) => [...prevBlocks, block]);
  };

  const handleEditClick = (block: Block) => {
    setBlockToEdit(block);
    setIsEditModalOpen(true);
  };

  const handleEditBlock = (updatedBlock: Block) => {
    setBlocks((prevBlocks) =>
      prevBlocks.map((block) =>
        block.id === updatedBlock.id ? updatedBlock : block
      )
    );
    setIsEditModalOpen(false);
    setBlockToEdit(null);

    setEditedBlockId(updatedBlock.id);
    setFlashSuccessId(updatedBlock.id);
    setTimeout(() => setFlashSuccessId(null), 1000);
    setTimeout(() => setEditedBlockId(null), 2000);
  };


  const cancel = () => {
    setIsAddModalOpen(false);
    setIsEditModalOpen(false);
  };

const handleReorderBlocks = async (updated: Block[]) => {
  setIsReordering(true);
  setBlocks(updated);
  try {
    await fetch('/block/reorder', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify(
        updated.map((b, idx) => ({ id: b.id, position: idx }))
      ),
    });
  } catch (e) {
    console.error('erreur lors de reclassement du block', e);
  } finally {
    setIsReordering(false);
  }
};

  return (
    <div className="relative">
      <div className="p-8">
        <div className="flex items-center mb-6">
          <h1 className="text-2xl font-bold whitespace-nowrap">Mes Compétences</h1>
          <AddButton onClick={() => setIsAddModalOpen(true)} />
        </div>
        <div className="relative">
          <BlockList
            blocks={blocks}
            onEdit={handleEditClick}
            onDelete={handleDeleteClick}
            onReorder={handleReorderBlocks}
          />

          <AnimatePresence>
            {isReordering && (
              <motion.div
                key="spinner"
                className="absolute inset-1 z-20 flex items-center justify-center pointer-events-auto"
                initial={{ opacity: 0, scale: 0.9 }}
                animate={{ opacity: 1, scale: 1 }}
                exit={{ opacity: 0, scale: 0.95 }}
                transition={{ duration: 0.3, ease: "easeInOut" }}
              >
                <Spinner className="w-12 h-12 text-blue-600" />
              </motion.div>
            )}
          </AnimatePresence>
        </div>
        {isAddModalOpen && (
          <AddBlockModal
            isOpen={isAddModalOpen}
            onClose={cancel}
            csrfToken={csrfToken}
            onAdd={handleAddBlock}
          />
        )}
        {isEditModalOpen && blockToEdit && (
          <EditBlockModal
            isOpen={isEditModalOpen}
            onClose={cancel}
            csrfToken={csrfToken}
            initialValues={{
              id: blockToEdit.id,
              title: blockToEdit.title,
              names: blockToEdit.names,
            }}
            onEdit={handleEditBlock}
          />
        )}
        <DeleteModal
          isOpen={isModalOpen}
          onConfirm={confirmDelete}
          onClose={cancelDelete}
        />
      </div>
    </div>
  );
};

export default BlockPage;