<?php

namespace App\Tests\Controller;

use App\Entity\Block;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

final class BlockControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $blockRepository;
    private string $path = '/block/';
    private User $user;
    private User $otherUser;

    protected function setUp(): void
    {
        $this->client = BlockControllerTest::createClient();
        $this->manager = BlockControllerTest::getContainer()->get('doctrine')->getManager();
        $this->blockRepository = $this->manager->getRepository(Block::class);

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
        self::assertPageTitleContains('Block index');
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'block[title]' => 'Languages',
            'block[names]' => ['PHP', 'TypeScript'],
            'block[user]' => $this->user->getId(),
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->blockRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Block();
        $fixture->setTitle('Languages');
        $fixture->setNames(['PHP', 'TypeScript']);
        $fixture->setUser($this->user->getId());

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Block');
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Block();
        $fixture->setTitle('Languages');
        $fixture->setNames(['PHP', 'TypeScript']);
        $fixture->setUser($this->user);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->loginUser($this->otherUser);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'block[title]' => 'Software',
            'block[names]' => ['PHPStorm', 'WebStorm'],
            'block[user]' => $this->otherUser->getId(),
        ]);

        self::assertResponseStatusCodeSame(403);

        $fixture = $this->blockRepository->find($fixture->getId());

        self::assertSame('Languages', $fixture->getTitle());
        self::assertSame(['PHP', 'TypeScript'], $fixture->getNames());
    }


    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Block();
        $fixture->setTitle('Languages');
        $fixture->setNames(['PHPStorm', 'WebStorm']);
        $fixture->setUser($this->user->getId());

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/block/');
        self::assertSame(0, $this->blockRepository->count([]));
    }
}
