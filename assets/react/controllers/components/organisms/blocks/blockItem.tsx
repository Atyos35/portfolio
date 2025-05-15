import React from 'react';
import { Block } from '../../../models/blocks/block.model';

interface BlockItemProps {
  block: Block;
}

const BlockItem: React.FC<BlockItemProps> = ({ block }) => {
  return (
    <div className="p-4 border rounded shadow-sm">
      <h3 className="text-lg font-semibold">{block.title}</h3>
      <div className="text-sm text-gray-600 space-x-1 mt-2">
        {block.names.map((name, index) => (
          <span
            key={index}
            className="inline-block bg-gray-200 rounded px-2 py-1 text-xs"
          >
            {name.value}
          </span>
        ))}
      </div>
    </div>
  );
};

export default BlockItem;