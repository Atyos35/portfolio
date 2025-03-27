<?php

namespace App\Tests\Controller;

use App\Entity\Training;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

final class TrainingControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $trainingRepository;
    private string $path = '/training/';
    private User $user;
    private User $otherUser;

    protected function setUp(): void
    {
        $this->client = TrainingControllerTest::createClient();
        $this->manager = TrainingControllerTest::getContainer()->get('doctrine')->getManager();
        $this->trainingRepository = $this->manager->getRepository(Training::class);

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
        self::assertPageTitleContains('Training index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'training[name]' => 'Développeur Web',
            'training[description]' => 'Titre de niveau III',
            'training[start_date]' => '2023-01-01',
            'training[end_date]' => '2023-12-31',
            'training[school]' => 'IMIE',
            'training[user]' => $this->user->getId(),
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->trainingRepository->count([]));
    }

    public function testShow(): void
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

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Training');

        // Use assertions to check that the properties are properly displayed.
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
    }

    public function testRemove(): void
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

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/training/');
        self::assertSame(0, $this->trainingRepository->count([]));
    }
}
