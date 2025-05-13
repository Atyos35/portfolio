import React from 'react';
import { Block } from '../../../models/blocks/block.model';
import BlockItem from './blockItem';

interface BlockListProps {
  blocks: Block[];
}

const BlockList: React.FC<BlockListProps> = ({
  blocks,
}) => {
  return (
    <div className="space-y-4">
      {blocks.map((block) => (
        <BlockItem
          key={block.id}
          block={block}
        />
      ))}
    </div>
  );
};

export default BlockList;