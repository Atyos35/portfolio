<?php

namespace App\Tests\Controller;

use App\Entity\Experience;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

final class ExperienceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $experienceRepository;
    private string $path = '/experience/';
    private User $user;
    private User $otherUser;

    protected function setUp(): void
    {
        $this->client = ExperienceControllerTest::createClient();
        $this->manager = ExperienceControllerTest::getContainer()->get('doctrine')->getManager();
        $this->experienceRepository = $this->manager->getRepository(Experience::class);

        $connection = $this->manager->getConnection();
        $schemaManager = $connection->createSchemaManager();

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($schemaManager->listTableNames() as $tableName) {
            $connection->executeStatement('TRUNCATE TABLE ' . $tableName);
        }

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1;');

        $this->user = new User();
        $this->user->setEmail('test@example.com');
        $this->user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->user->setFirstname('John');
        $this->user->setLastname('Doe');
        $this->user->setJob('Développeur');
        $this->user->setLinkedin('https://linkedin.com/in/johndoe');
        $this->user->setAge('30');
        $this->user->setCity('Paris');
        $this->user->setPhone('0606060606');
        $this->manager->persist($this->user);

        $this->otherUser = new User();
        $this->otherUser->setEmail('other@example.com');
        $this->otherUser->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->otherUser->setFirstname('Jane');
        $this->otherUser->setLastname('Doe');
        $this->otherUser->setJob('Designer');
        $this->otherUser->setLinkedin('https://linkedin.com/in/janedoe');
        $this->otherUser->setAge('28');
        $this->otherUser->setCity('Lyon');
        $this->otherUser->setPhone('0707070707');
        $this->manager->persist($this->otherUser);

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Experience index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'experience[name]' => 'Développeur Symfony',
            'experience[start_date]' => '2023-01-01',
            'experience[end_date]' => '2023-12-31',
            'experience[description]' => 'Développement d\'applications web en Symfony.',
            'experience[company]' => 'TechCorp',
            'experience[city]' => 'Paris',
            'experience[user]' => $this->user->getId(),
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->experienceRepository->count([]));
    }


    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Experience();
        $fixture->setName('Ingénieur DevOps');
        $fixture->setStartDate(new \DateTime('2022-06-01'));
        $fixture->setEndDate(new \DateTime('2023-06-01'));
        $fixture->setDescription('Mise en place et gestion d\'infrastructure cloud.');
        $fixture->setCompany('CloudX');
        $fixture->setCity('Lyon');
        $fixture->setUser($this->user);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Experience');
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Training();
        $fixture->setName('Développeur Web');
        $fixture->setDescription('Titre de niveau III');
        $fixture->setStart_date(new \DateTime('2022-06-01'));
        $fixture->setEnd_date(new \DateTime('2023-06-01'));
        $fixture->setSchool('IMIE');
        $fixture->setUser($this->user);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'training[name]' => 'Développeur Full Stack',
            'training[description]' => 'Titre de niveau 12',
            'training[start_date]' => '2022-01-01',
            'training[end_date]' => '2023-01-01',
            'training[school]' => 'Owford',
            'training[user]' => $this->user->getId(),
        ]);

        self::assertResponseRedirects('/training/');
        
        $fixture = $this->trainingRepository->findAll();

        self::assertSame('Développeur Web', $fixture[0]->getName());
        self::assertSame('Titre de niveau III', $fixture[0]->getDescription());
        self::assertEquals(new \DateTime('2022-06-01'), $fixture[0]->getStart_date());
        self::assertEquals(new \DateTime('2023-06-01'), $fixture[0]->getEnd_date());
        self::assertSame('IMIE', $fixture[0]->getSchool());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Experience();
        $fixture->setName('Tech Lead');
        $fixture->setStartDate(new \DateTime('2020-05-01'));
        $fixture->setEndDate(new \DateTime('2022-05-01'));
        $fixture->setDescription('Encadrement d\'une équipe de développement.');
        $fixture->setCompany('TechX');
        $fixture->setCity('Toulouse');
        $fixture->setUser($this->user);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/experience/');
        self::assertSame(0, $this->experienceRepository->count([]));
    }

}
