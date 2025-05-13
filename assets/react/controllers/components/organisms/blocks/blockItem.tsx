import React from 'react';
import { Block } from '../../../models/blocks/block.model';

interface BlockItemProps {
  block: Block;
}

const BlockItem: React.FC<BlockItemProps> = ({
  block,
}) => {
  return (
    <div className="p-4 border rounded shadow-sm flex justify-between items-center">
      <div>
        <h3 className="text-lg font-semibold">{block.title}</h3>
          <div className="text-sm text-gray-600 space-x-1">
            {block.names.map((name, index) => (
              <span key={index} className="inline-block bg-gray-200 rounded px-2 py-1 text-xs">
                {name}
              </span>
            ))}
          </div>
      </div>
      <div className="flex space-x-2">
      </div>
    </div>
  );
};

export default BlockItem;