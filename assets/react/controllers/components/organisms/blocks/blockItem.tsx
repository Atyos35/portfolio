import React from 'react';
import { Block } from '../../../models/blocks/block.model';

interface BlockItemProps {
  block: Block;
}

const BlockItem: React.FC<BlockItemProps> = ({ block }) => {
  return (
    <div className="w-full h-full p-4 border border-gray-300 rounded-2xl shadow-sm bg-white cursor-grab">
      <h3 className="text-lg font-semibold">{block.title}</h3>
      <div className="text-sm text-gray-600 space-x-1 mt-2">
        {block.names.map((name, index) => (
          <span
            key={index}
            className="inline-block border border-gray-300 rounded-2xl shadow-sm px-2 py-1 text-xs"
          >
            {name.value}
          </span>
        ))}
      </div>
    </div>
  );
};

export default BlockItem;