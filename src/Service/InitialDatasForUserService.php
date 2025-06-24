<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Experience;
use App\Entity\Training;
use App\Entity\Block;
use Doctrine\ORM\EntityManagerInterface;

class InitialDatasForUserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createInitialDatasForUser(User $user): void
    {
        $experience = new Experience();
        $experience->setName("Exemple d'expérience");
        $experience->setCompany("GooglePlex");
        $experience->setCity("Mountain View");
        $experience->setStartDate(new \DateTime("2023-01-01"));
        $experience->setEndDate(null);
        $experience->setDescription("Ceci est une expérience d'exemple, modifiez la avec vos informations !");
        $experience->setDuration(null);
        $user->addExperience($experience);
        $this->entityManager->persist($experience);

        $training = new Training();
        $training->setName("Exemple de formation");
        $training->setDescription("Ceci est une formation d'exemple, modifiez la avec vos informations !");
        $training->setSchool("GoogleSchool");
        $training->setStartDate(new \DateTime("2021-09-01"));
        $training->setEndDate(new \DateTime("2023-06-30"));
        $training->setCity("Mountain View");
        $user->addTraining($training);
        $this->entityManager->persist($training);

        $block = new Block();
        $block->setTitle("Bloc d'exemple");
        $block->setNames(["PHP", "JavaScript"]);
        $block->setPosition(1);
        $user->addBlock($block);
        $this->entityManager->persist($block);

        $this->entityManager->flush();
    }
}