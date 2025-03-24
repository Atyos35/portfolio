<?php

namespace App\Tests\Controller;

use App\Entity\Experience;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ExperienceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $experienceRepository;
    private string $path = '/experience/';

    protected function setUp(): void
    {
        $this->client = ExperienceControllerTest::createClient();
        $this->manager = ExperienceControllerTest::getContainer()->get('doctrine')->getManager();
        $this->experienceRepository = $this->manager->getRepository(Experience::class);

        foreach ($this->experienceRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

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
            'experience[name]' => 'Testing',
            'experience[start_date]' => 'Testing',
            'experience[end_date]' => 'Testing',
            'experience[description]' => 'Testing',
            'experience[duration]' => 'Testing',
            'experience[company]' => 'Testing',
            'experience[city]' => 'Testing',
            'experience[user]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->experienceRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Experience();
        $fixture->setName('My Title');
        $fixture->setStart_date('My Title');
        $fixture->setEnd_date('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDuration('My Title');
        $fixture->setCompany('My Title');
        $fixture->setCity('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Experience');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Experience();
        $fixture->setName('Value');
        $fixture->setStart_date('Value');
        $fixture->setEnd_date('Value');
        $fixture->setDescription('Value');
        $fixture->setDuration('Value');
        $fixture->setCompany('Value');
        $fixture->setCity('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'experience[name]' => 'Something New',
            'experience[start_date]' => 'Something New',
            'experience[end_date]' => 'Something New',
            'experience[description]' => 'Something New',
            'experience[duration]' => 'Something New',
            'experience[company]' => 'Something New',
            'experience[city]' => 'Something New',
            'experience[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/experience/');

        $fixture = $this->experienceRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getStart_date());
        self::assertSame('Something New', $fixture[0]->getEnd_date());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getDuration());
        self::assertSame('Something New', $fixture[0]->getCompany());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Experience();
        $fixture->setName('Value');
        $fixture->setStart_date('Value');
        $fixture->setEnd_date('Value');
        $fixture->setDescription('Value');
        $fixture->setDuration('Value');
        $fixture->setCompany('Value');
        $fixture->setCity('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/experience/');
        self::assertSame(0, $this->experienceRepository->count([]));
    }
}
