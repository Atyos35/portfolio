import React, { useState } from 'react';
import BlockList from '../organisms/blocks/blockList';
import { Block } from '../../models/blocks/block.model';
import AddButton from '../../components/atoms/addButton';
import AddBlockModal from '../../components/organisms/blocks/addBlockModal';

interface BlockPageProps {
  blocks: Block[];
  csrfToken: string;
}

const BlockPage: React.FC<BlockPageProps> = ({ blocks: initialBlock, csrfToken }) => {
  
  const [blocks, setBlocks] = useState<Block[]>(initialBlock);
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);

  const handleAddTraining = (block: Block) => {
    setBlocks((prevBlocks) => [...prevBlocks, block]);
  };  

  const cancel = () => {
    setIsAddModalOpen(false);;
  };

  return (
    <div className="p-8">
      <h1 className="text-2xl font-bold mb-6">
        Mes Compétences
        <AddButton onClick={() => setIsAddModalOpen(true)} />
      </h1>
      <BlockList
        blocks={blocks}
      />
      {isAddModalOpen && (
        <AddBlockModal
          isOpen={isAddModalOpen}
          onClose={cancel}
          csrfToken={csrfToken}
          onAdd={handleAddTraining}
        />
      )}
    </div>
  );
};

export default BlockPage;