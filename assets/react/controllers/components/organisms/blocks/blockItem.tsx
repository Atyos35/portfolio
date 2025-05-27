import React from 'react';
import { Block } from '../../../models/blocks/block.model';
import { motion } from 'framer-motion';

interface BlockItemProps {
  block: Block;
  flash?: boolean;
}

const BlockItem: React.FC<BlockItemProps> = ({ 
  block, 
  flash 
}) => {
  return (
    <motion.div
      initial={false}
      animate={{}}
      style={{
        backgroundColor: flash ? '#E6F4EA' : 'rgba(230, 244, 234, 0)',
      }}
      transition={{ duration: 0.5 }}
      className="w-full h-full p-4 border border-gray-300 rounded-2xl shadow-sm cursor-grab"
    >
      <div>
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
    </motion.div>
  );
};

export default BlockItem;