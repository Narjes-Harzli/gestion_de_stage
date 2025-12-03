<?php

namespace App\Tests\Controller;

use App\Entity\Etudiant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EtudiantControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $etudiantRepository;
    private string $path = '/etudiant/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->etudiantRepository = $this->manager->getRepository(Etudiant::class);

        foreach ($this->etudiantRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etudiant index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'etudiant[nom]' => 'Testing',
            'etudiant[prenom]' => 'Testing',
            'etudiant[email]' => 'Testing',
            'etudiant[telephone]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->etudiantRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etudiant();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setEmail('My Title');
        $fixture->setTelephone('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etudiant');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etudiant();
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setEmail('Value');
        $fixture->setTelephone('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'etudiant[nom]' => 'Something New',
            'etudiant[prenom]' => 'Something New',
            'etudiant[email]' => 'Something New',
            'etudiant[telephone]' => 'Something New',
        ]);

        self::assertResponseRedirects('/etudiant/');

        $fixture = $this->etudiantRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getTelephone());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etudiant();
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setEmail('Value');
        $fixture->setTelephone('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/etudiant/');
        self::assertSame(0, $this->etudiantRepository->count([]));
    }
}
