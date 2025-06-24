<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\Experience;
use App\Entity\Training;
use App\Entity\Block;
use App\Service\InitialDatasForUserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class InitialDatasForUserServiceTest extends TestCase
{
    public function testCreateInitialDatasForUser(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $user = $this->createMock(User::class);

        $entityManager->expects($this->exactly(3))
            ->method('persist')
            ->with($this->callback(function ($entity) {
                return $entity instanceof Experience || $entity instanceof Training || $entity instanceof Block;
            }));

        $entityManager->expects($this->once())
            ->method('flush');

        $user->expects($this->once())
            ->method('addExperience')
            ->with($this->callback(function ($experience) {
                return $experience instanceof Experience
                    && $experience->getName() === "Exemple d'expérience"
                    && $experience->getCompany() === "GooglePlex"
                    && $experience->getCity() === "Mountain View"
                    && $experience->getDescription() === "Ceci est une expérience d'exemple, modifiez la avec vos informations !";
            }));

        $user->expects($this->once())
            ->method('addTraining')
            ->with($this->callback(function ($training) {
                return $training instanceof Training
                    && $training->getName() === "Exemple de formation"
                    && $training->getSchool() === "GoogleSchool"
                    && $training->getCity() === "Mountain View";
            }));

        $user->expects($this->once())
            ->method('addBlock')
            ->with($this->callback(function ($block) {
                return $block instanceof Block
                    && $block->getTitle() === "Bloc d'exemple"
                    && $block->getNames() === ["PHP", "JavaScript"]
                    && $block->getPosition() === 1;
            }));

        $service = new InitialDatasForUserService($entityManager);
        $service->createInitialDatasForUser($user);
    }
}