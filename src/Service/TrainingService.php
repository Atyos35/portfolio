<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\TrainingRepository;

class TrainingService
{
    private TrainingRepository $trainingRepository;

    public function __construct(TrainingRepository $trainingRepository)
    {
        $this->trainingRepository = $trainingRepository;
    }

    public function getUserTrainings(User $user): array
    {
        $trainings = $this->trainingRepository->findAllByUser($user->getId());
        
        return array_map(function ($training) {
            return [
                'id' => $training->getId(),
                'name' => $training->getName(),
                'start_date' => $training->getStartDate()?->format('Y-m-d'),
                'end_date' => $training->getEndDate()?->format('Y-m-d'),
                'description' => $training->getDescription(),
                'duration' => $training->getDuration(),
                'school' => $training->getSchool(),
                'city' => $training->getCity(),
            ];
        }, $trainings);
    }
}
