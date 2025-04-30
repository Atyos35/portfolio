<?php
namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\Experience;
use App\Repository\ExperienceRepository;
use App\Service\ExperienceService;
use PHPUnit\Framework\TestCase;

class ExperienceServiceTest extends TestCase
{
    public function testGetUserExperiences(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(1);

        $experience1 = $this->createMock(Experience::class);
        $experience1->method('getId')->willReturn(1);
        $experience1->method('getName')->willReturn('Développeur Symfony');
        $experience1->method('getStartDate')->willReturn(new \DateTime('2020-01-01'));
        $experience1->method('getEndDate')->willReturn(new \DateTime('2022-01-01'));
        $experience1->method('getDescription')->willReturn('Développement de projets Symfony');
        $experience1->method('getDuration')->willReturn(new \DateInterval('P2Y'));
        $experience1->method('getCompany')->willReturn('TechCorp');
        $experience1->method('getCity')->willReturn('Paris');

        $experience2 = $this->createMock(Experience::class);
        $experience2->method('getId')->willReturn(2);
        $experience2->method('getName')->willReturn('Développeur Frontend');
        $experience2->method('getStartDate')->willReturn(new \DateTime('2018-01-01'));
        $experience2->method('getEndDate')->willReturn(new \DateTime('2020-01-01'));
        $experience2->method('getDescription')->willReturn('Développement d\'interfaces utilisateurs');
        $experience2->method('getDuration')->willReturn(new \DateInterval('P2Y'));
        $experience2->method('getCompany')->willReturn('WebDev Ltd');
        $experience2->method('getCity')->willReturn('Lyon');

        $experienceRepository = $this->createMock(ExperienceRepository::class);
        $experienceRepository->method('findAllByUser')->willReturn([$experience1, $experience2]);

        $experienceService = new ExperienceService($experienceRepository);

        $result = $experienceService->getUserExperiences($user);

        $this->assertCount(2, $result);

        $this->assertInstanceOf(\DateInterval::class, $result[0]['duration']);
        $this->assertEquals('P2Y', $result[0]['duration']->format('P%yY'));

        $this->assertEquals([
            'id' => 1,
            'name' => 'Développeur Symfony',
            'start_date' => '2020-01-01',
            'end_date' => '2022-01-01',
            'description' => 'Développement de projets Symfony',
            'duration' => new \DateInterval('P2Y'),
            'company' => 'TechCorp',
            'city' => 'Paris',
        ], $result[0]);

        $this->assertEquals([
            'id' => 2,
            'name' => 'Développeur Frontend',
            'start_date' => '2018-01-01',
            'end_date' => '2020-01-01',
            'description' => 'Développement d\'interfaces utilisateurs',
            'duration' => new \DateInterval('P2Y'),
            'company' => 'WebDev Ltd',
            'city' => 'Lyon',
        ], $result[1]);
    }
}