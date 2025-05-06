<?php
namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\Training;
use App\Repository\TrainingRepository;
use App\Service\TrainingService;
use PHPUnit\Framework\TestCase;

class TrainingServiceTest extends TestCase
{
    public function testGetUserTrainings(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(1);

        $training1 = $this->createMock(Training::class);
        $training1->method('getId')->willReturn(1);
        $training1->method('getName')->willReturn('Développeur Symfony');
        $training1->method('getStartDate')->willReturn(new \DateTime('2020-01-01'));
        $training1->method('getEndDate')->willReturn(new \DateTime('2022-01-01'));
        $training1->method('getDescription')->willReturn('Développement de projets Symfony');
        $training1->method('getDuration')->willReturn(new \DateInterval('P2Y'));
        $training1->method('getSchool')->willReturn('TechSchool');
        $training1->method('getCity')->willReturn('Paris');

        $training2 = $this->createMock(Training::class);
        $training2->method('getId')->willReturn(2);
        $training2->method('getName')->willReturn('Développeur Frontend');
        $training2->method('getStartDate')->willReturn(new \DateTime('2018-01-01'));
        $training2->method('getEndDate')->willReturn(new \DateTime('2020-01-01'));
        $training2->method('getDescription')->willReturn('Développement d\'interfaces utilisateurs');
        $training2->method('getDuration')->willReturn(new \DateInterval('P2Y'));
        $training2->method('getSchool')->willReturn('WebDev School');
        $training2->method('getCity')->willReturn('Lyon');

        $trainingRepository = $this->createMock(TrainingRepository::class);
        $trainingRepository->method('findAllByUser')->willReturn([$training1, $training2]);

        $trainingService = new TrainingService($trainingRepository);

        $result = $trainingService->getUserTrainings($user);

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
            'school' => 'TechSchool',
            'city' => 'Paris',
        ], $result[0]);

        $this->assertEquals([
            'id' => 2,
            'name' => 'Développeur Frontend',
            'start_date' => '2018-01-01',
            'end_date' => '2020-01-01',
            'description' => 'Développement d\'interfaces utilisateurs',
            'duration' => new \DateInterval('P2Y'),
            'school' => 'WebDev School',
            'city' => 'Lyon',
        ], $result[1]);
    }
}