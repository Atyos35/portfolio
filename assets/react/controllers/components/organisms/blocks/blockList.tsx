import React, { useState } from 'react';
import { Block } from '../../../models/blocks/block.model';
import BlockItem from './blockItem';
import { DndContext, DragEndEvent } from '@dnd-kit/core';
import { Draggable } from '../../../utils/dnd/Draggable';
import { Droppable } from '../../../utils/dnd/Droppable';
import EditButton from '../../atoms/editButton';

interface BlockListProps {
  blocks: Block[];
  onEdit: (values: any) => void;
}

const BlockList: React.FC<BlockListProps> = ({ blocks: initialBlocks, onEdit }) => {
  const mappedBlocks = initialBlocks.map(block => ({
    ...block,
    names: block.names.map(name =>
      typeof name === 'string' ? { value: name } : name
    ),
  }));

  const [blocks, setBlocks] = useState<Block[]>(mappedBlocks);

  const handleDragEnd = (event: DragEndEvent) => {
    const { active, over } = event;

    if (over && active.id !== over.id) {
      const oldIndex = blocks.findIndex(block => block.id === active.id);
      const newIndex = blocks.findIndex(block => block.id === over.id);

      if (oldIndex !== -1 && newIndex !== -1) {
        const newBlocks = [...blocks];
        const [moved] = newBlocks.splice(oldIndex, 1);
        newBlocks.splice(newIndex, 0, moved);
        setBlocks(newBlocks);
      }
    }
  };

  return (
    <DndContext onDragEnd={handleDragEnd}>
      <div className="space-y-4">
        {blocks.map((block) => (
          <Droppable key={block.id} id={block.id}>
          <div className="flex justify-between items-center">
            <Draggable id={block.id}>
              <BlockItem block={block} />
            </Draggable>
            <EditButton onClick={() => onEdit(block)} />
          </div>
        </Droppable>
        ))}
      </div>
    </DndContext>
  );
};

export default BlockList;