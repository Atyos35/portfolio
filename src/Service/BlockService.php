<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\BlockRepository;

class BlockService
{
    private BlockRepository $blockRepository;

    public function __construct(BlockRepository $blockRepository)
    {
        $this->blockRepository = $blockRepository;
    }

    public function getUserBlocks(User $user): array
    {
        $blocks = $this->blockRepository->findAllByUser($user->getId());
        
        return array_map(function ($block) {
            return [
                'id' => $block->getId(),
                'title' => $block->getTitle(),
                'names' => $block->getNames(),
            ];
        }, $blocks);
    }

    public function getNextBlockPosition(User $user): array
    {
        return ['next_position' => $this->blockRepository->findNextPositionForBlockByUser($user)];
    }
}
