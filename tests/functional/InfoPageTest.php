<?php

namespace App\tests\functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InformatiquePageTest extends WebTestCase
{
    public function testVideosPageWorks(): void
    {
        $client = static::createClient();
        $crawler =  $client->request('GET', '/informatique');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('h1', 'Informatique');

//        $link = $crawler->selectLink('Recettes')->extract(['href'])[0];
//        $crawler = $client->request(Request::METHOD_GET, $link);
//
//        $this->assertResponseIsSuccessful();
//        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
//
    }

}
