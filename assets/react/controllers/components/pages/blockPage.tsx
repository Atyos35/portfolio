import React, { useState } from 'react';
import BlockList from '../organisms/blocks/blockList';
import { Block } from '../../models/blocks/block.model';
import AddButton from '../../components/atoms/addButton';
import AddBlockModal from '../../components/organisms/blocks/addBlockModal';
import EditBlockModal from '../../components/organisms/blocks/editBlockModal';

interface BlockPageProps {
  blocks: Block[];
  csrfToken: string;
}

const BlockPage: React.FC<BlockPageProps> = ({ blocks: initialBlock, csrfToken }) => {

  const [blocks, setBlocks] = useState<Block[]>(initialBlock);
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [blockToEdit, setBlockToEdit] = useState<Block | null>(null);

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
  };


  const cancel = () => {
    setIsAddModalOpen(false);
    setIsEditModalOpen(false);
  };

  return (
    <div className="p-8">
      <h1 className="text-2xl font-bold mb-6">
        Mes Compétences
        <AddButton onClick={() => setIsAddModalOpen(true)} />
      </h1>
      <BlockList
        blocks={blocks}
        onEdit={handleEditClick}
      />
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
    </div>
  );
};

export default BlockPage;