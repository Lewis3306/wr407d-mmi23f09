<?php

namespace App\Tests\Controller;

use App\Entity\Voiture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class VoitureControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $voitureRepository;
    private string $path = '/voiture/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->voitureRepository = $this->manager->getRepository(Voiture::class);

        foreach ($this->voitureRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Voiture index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'voiture[marque]' => 'Testing',
            'voiture[modele]' => 'Testing',
            'voiture[prix_location]' => 'Testing',
            'voiture[description]' => 'Testing',
            'voiture[garage]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->voitureRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voiture();
        $fixture->setMarque('My Title');
        $fixture->setModele('My Title');
        $fixture->setPrix_location('My Title');
        $fixture->setDescription('My Title');
        $fixture->setGarage('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Voiture');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voiture();
        $fixture->setMarque('Value');
        $fixture->setModele('Value');
        $fixture->setPrix_location('Value');
        $fixture->setDescription('Value');
        $fixture->setGarage('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'voiture[marque]' => 'Something New',
            'voiture[modele]' => 'Something New',
            'voiture[prix_location]' => 'Something New',
            'voiture[description]' => 'Something New',
            'voiture[garage]' => 'Something New',
        ]);

        self::assertResponseRedirects('/voiture/');

        $fixture = $this->voitureRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getMarque());
        self::assertSame('Something New', $fixture[0]->getModele());
        self::assertSame('Something New', $fixture[0]->getPrix_location());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getGarage());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voiture();
        $fixture->setMarque('Value');
        $fixture->setModele('Value');
        $fixture->setPrix_location('Value');
        $fixture->setDescription('Value');
        $fixture->setGarage('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/voiture/');
        self::assertSame(0, $this->voitureRepository->count([]));
    }
}
