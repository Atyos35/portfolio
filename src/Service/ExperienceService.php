<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ExperienceRepository;

class ExperienceService
{
    private ExperienceRepository $experienceRepository;

    public function __construct(ExperienceRepository $experienceRepository)
    {
        $this->experienceRepository = $experienceRepository;
    }

    public function getUserExperiences(User $user): array
    {
        $experiences = $this->experienceRepository->findAllByUser($user->getId());
        
        return array_map(function ($experience) {
            return [
                'id' => $experience->getId(),
                'name' => $experience->getName(),
                'start_date' => $experience->getStartDate()?->format('Y-m-d'),
                'end_date' => $experience->getEndDate()?->format('Y-m-d'),
                'description' => $experience->getDescription(),
                'duration' => $experience->getDuration(),
                'company' => $experience->getCompany(),
                'city' => $experience->getCity(),
            ];
        }, $experiences);
    }
}
