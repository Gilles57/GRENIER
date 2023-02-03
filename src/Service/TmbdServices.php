<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TmbdServices
{
    public function __construct(private HttpClientInterface $client,)
    {
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function findData(
        string  $titre,
        ?string $annee,
    ): array
    {
        $retour = ["non !"];

        $titre = preg_replace("/[^a-zA-Z0-9\s]/", "", $titre);
        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/search/movie?api_key=c7924bfc3e4208e9e6eafb5beaee9940&query=' . $titre . '&language=fr'
        );
        $temp = json_decode($response->getContent(), true);
        $results = $temp['results'];
        foreach ($results as $result) {
            dump($result);
            if ($annee == null || ($annee == substr($result['release_date'], 0, 4))) {
                $id = $result['id'];

                // À partir de l'id, on va chercher la fiche détaillée
                $response = $this->client->request(
                    'GET',
                    'https://api.themoviedb.org/3/movie/' . $id . '?api_key=c7924bfc3e4208e9e6eafb5beaee9940&language=fr'
                );
                $retour=  json_decode($response->getContent(), true);
            }
        }
        return $retour;
    }
}