<?php

namespace App\Test\Controller;

use App\Entity\Import;
use App\Repository\ImportRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImportControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ImportRepository $repository;
    private string $path = '/import/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Import::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Import index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'import[fichier]' => 'Testing',
            'import[title]' => 'Testing',
            'import[extension]' => 'Testing',
            'import[langue]' => 'Testing',
            'import[annee]' => 'Testing',
        ]);

        self::assertResponseRedirects('/import/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Import();
        $fixture->setFichier('My Title');
        $fixture->setTitle('My Title');
        $fixture->setExtension('My Title');
        $fixture->setLangue('My Title');
        $fixture->setAnnee('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Import');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Import();
        $fixture->setFichier('My Title');
        $fixture->setTitle('My Title');
        $fixture->setExtension('My Title');
        $fixture->setLangue('My Title');
        $fixture->setAnnee('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'import[fichier]' => 'Something New',
            'import[title]' => 'Something New',
            'import[extension]' => 'Something New',
            'import[langue]' => 'Something New',
            'import[annee]' => 'Something New',
        ]);

        self::assertResponseRedirects('/import/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getFichier());
        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getExtension());
        self::assertSame('Something New', $fixture[0]->getLangue());
        self::assertSame('Something New', $fixture[0]->getAnnee());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Import();
        $fixture->setFichier('My Title');
        $fixture->setTitle('My Title');
        $fixture->setExtension('My Title');
        $fixture->setLangue('My Title');
        $fixture->setAnnee('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/import/');
    }
}
