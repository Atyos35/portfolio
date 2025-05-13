<?php
namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\Block;
use App\Repository\BlockRepository;
use App\Service\BlockService;
use PHPUnit\Framework\TestCase;

class BlockServiceTest extends TestCase
{
    public function testGetUserBlocks(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(1);

        $block1 = $this->createMock(Block::class);
        $block1->method('getId')->willReturn(1);
        $block1->method('getTitle')->willReturn('Langage');
        $block1->method('getNames')->willReturn(['PHP', 'JavaScript', "Python"]);

        $block2 = $this->createMock(Block::class);
        $block2->method('getId')->willReturn(2);
        $block2->method('getTitle')->willReturn('frameworks');
        $block2->method('getNames')->willReturn(['Symfony', 'API Platform', "NestJS"]);

        $blockRepository = $this->createMock(BlockRepository::class);
        $blockRepository->method('findAllByUser')->willReturn([$block1, $block2]);

        $blockService = new BlockService($blockRepository);

        $result = $blockService->getUserBlocks($user);

        $this->assertCount(2, $result);

        $this->assertEquals([
            'id' => 1,
            'title' => 'Langage',
            'names' => ['PHP', 'JavaScript', "Python"],
        ], $result[0]);

        $this->assertEquals([
            'id' => 2,
            'title' => 'frameworks',
            'names' => ['Symfony', 'API Platform', "NestJS"],
        ], $result[1]);
    }
}