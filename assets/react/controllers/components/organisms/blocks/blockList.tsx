import React from 'react';
import { Block } from '../../../models/blocks/block.model';
import BlockItem from './blockItem';
import { DndContext, DragEndEvent } from '@dnd-kit/core';
import { Draggable } from '../../../utils/dnd/Draggable';
import { Droppable } from '../../../utils/dnd/Droppable';
import EditButton from '../../atoms/editButton';
import DeleteButton from '../../atoms/deleteButton';
import { motion, AnimatePresence } from 'framer-motion';
import CheckIcon from '../../atoms/checkIcon';

interface BlockListProps {
  blocks: Block[];
  onEdit: (block: Block) => void;
  onDelete: (id: number) => void;
  onReorder: (updated: Block[]) => void;
  showCheckIcon?: boolean;
  flashSuccessId?: number;
}

const BlockList: React.FC<BlockListProps> = ({ 
  blocks, 
  onEdit, 
  onDelete, 
  onReorder ,
  showCheckIcon,
  flashSuccessId
}) => {

  const mappedBlocks = blocks.map(b => ({
    ...b,
    names: b.names.map(n => (typeof n === 'string' ? { value: n } : n)),
  }));

  const handleDragEnd = (event: DragEndEvent) => {
    const { active, over } = event;
    if (!over || active.id === over.id) return;

    const oldIndex = blocks.findIndex(b => b.id === active.id);
    const newIndex = blocks.findIndex(b => b.id === over.id);
    if (oldIndex === -1 || newIndex === -1) return;

    const updatedBlocks = [...blocks];
    const [moved] = updatedBlocks.splice(oldIndex, 1);
    updatedBlocks.splice(newIndex, 0, moved);
    
    onReorder(updatedBlocks);
  };

  return (
    <DndContext onDragEnd={handleDragEnd}>
      <div className="space-y-4">
        {mappedBlocks.map(block => (
          <Droppable key={block.id} id={block.id}>
            <div className="flex items-center justify-between gap-4 w-full">
              <div className="flex-1">
                <Draggable id={block.id}>
                  <BlockItem block={block} flash={flashSuccessId === block.id} />
                </Draggable>
              </div>
              <div className="flex gap-2 shrink-0">
                <AnimatePresence mode="wait">
                  {showCheckIcon ? (
                    <motion.div
                      key="check"
                      initial={{ opacity: 0, scale: 0.8 }}
                      animate={{ opacity: 1, scale: 1 }}
                      exit={{ opacity: 0, scale: 0.8 }}
                      transition={{ duration: 0.3 }}
                      className="rounded-full shadow"
                    >
                      <CheckIcon />
                    </motion.div>
                  ) : (
                    <motion.div
                      key="edit"
                      initial={{ opacity: 0, scale: 0.8 }}
                      animate={{ opacity: 1, scale: 1 }}
                      exit={{ opacity: 0, scale: 0.8 }}
                      transition={{ duration: 0.3 }}
                    >
                      <EditButton onClick={() => onEdit(block)} />
                      <DeleteButton onClick={() => onDelete(block.id)} />
                    </motion.div>
                  )}
                </AnimatePresence>
              </div>
            </div>
          </Droppable>
        ))}
      </div>
    </DndContext>
  );
};

export default BlockList;