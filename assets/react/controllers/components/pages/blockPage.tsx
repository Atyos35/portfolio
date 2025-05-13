import React, { useState } from 'react';
import BlockList from '../organisms/blocks/blockList';
import { Block } from '../../models/blocks/block.model';

interface BlockPageProps {
  blocks: Block[];
}

const BlockPage: React.FC<BlockPageProps> = ({ blocks: initialBlock }) => {
  
  const [blocks] = useState<Block[]>(initialBlock);

  return (
    <div className="p-8">
      <h1 className="text-2xl font-bold mb-6">
        Mes Compétences
      </h1>
      <BlockList
        blocks={blocks}
      />
    </div>
  );
};

export default BlockPage;