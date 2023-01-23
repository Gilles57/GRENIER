<?php

namespace App\tests\functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testHomePageWorks(): void
    {
        $client = static::createClient();
        $crawler =  $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('h1', 'Bienvenue sur mon site');
        $cards = $crawler->filter('.card-img-top')->count();
        $this->assertEquals($cards, 4);
    }

    public function testRubriqueRecettesPageWorks(): void
    {
        $client = static::createClient();
        $crawler =  $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);


        $link = $crawler->selectLink('Recettes')->extract(['href'])[0];
        $crawler = $client->request(Request::METHOD_GET, $link);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('h1', 'Mes recettes préférées');
    }

    public function testRubriqueFilmsPageWorks(): void
    {
        $client = static::createClient();
        $crawler =  $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);


        $link = $crawler->selectLink('Films')->extract(['href'])[0];
        $crawler = $client->request(Request::METHOD_GET, $link);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('h1', 'Films et séries');
    }

    public function testRubriqueInformatiquePageWorks(): void
    {
        $client = static::createClient();
        $crawler =  $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);


        $link = $crawler->selectLink('Informatique')->extract(['href'])[0];
        $crawler = $client->request(Request::METHOD_GET, $link);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('h1', 'Informatique');
    }

    public function testRubriqueDiversPageWorks(): void
    {
        $client = static::createClient();
        $crawler =  $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);


        $link = $crawler->selectLink('Divers')->extract(['href'])[0];
        $crawler = $client->request(Request::METHOD_GET, $link);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('h1', 'Rubriques en vrac');
    }

}
