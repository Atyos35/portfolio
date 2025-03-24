<?php

namespace App\Tests\Controller;

use App\Entity\Training;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TrainingControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $trainingRepository;
    private string $path = '/training/';

    protected function setUp(): void
    {
        $this->client = TrainingControllerTest::createClient();
        $this->manager = TrainingControllerTest::getContainer()->get('doctrine')->getManager();
        $this->trainingRepository = $this->manager->getRepository(Training::class);

        foreach ($this->trainingRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

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
            'training[name]' => 'Testing',
            'training[description]' => 'Testing',
            'training[start_date]' => 'Testing',
            'training[end_date]' => 'Testing',
            'training[school]' => 'Testing',
            'training[user]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->trainingRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Training();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setStart_date('My Title');
        $fixture->setEnd_date('My Title');
        $fixture->setSchool('My Title');
        $fixture->setUser('My Title');

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
        $fixture->setName('Value');
        $fixture->setDescription('Value');
        $fixture->setStart_date('Value');
        $fixture->setEnd_date('Value');
        $fixture->setSchool('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'training[name]' => 'Something New',
            'training[description]' => 'Something New',
            'training[start_date]' => 'Something New',
            'training[end_date]' => 'Something New',
            'training[school]' => 'Something New',
            'training[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/training/');

        $fixture = $this->trainingRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getStart_date());
        self::assertSame('Something New', $fixture[0]->getEnd_date());
        self::assertSame('Something New', $fixture[0]->getSchool());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Training();
        $fixture->setName('Value');
        $fixture->setDescription('Value');
        $fixture->setStart_date('Value');
        $fixture->setEnd_date('Value');
        $fixture->setSchool('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/training/');
        self::assertSame(0, $this->trainingRepository->count([]));
    }
}
