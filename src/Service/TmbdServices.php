<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TmbdServices
{
    public function __construct(private HttpClientInterface $client,
                                private ParameterBagInterface $params)
    {
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function findData(
        string $titre,
    ): array
    {
        $retours = [];
        $key = $this->params->get('api_key_tmdb');

        $titre = preg_replace("/[^a-zA-Z0-9\s]/", "", $titre);
        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/search/movie?api_key=' . $key . '&query=' . $titre . '&language=fr'
        );
        $temp = json_decode($response->getContent(), true);
        $results = $temp['results'];
        foreach ($results as $result) {
            $id = $result['id'];
            // À partir de l'id, on va chercher la fiche détaillée
            $response = $this->client->request(
                'GET',
                'https://api.themoviedb.org/3/movie/' . $id . '?api_key=' . $key . '&language=fr'
            );
            $retours[] = json_decode($response->getContent(), true);
        }
        return $retours;
    }

    public function uploadAffiche($posterPath): void
    {
        $url = 'https://image.tmdb.org/t/p/w500' . $posterPath;
        $affiche = basename($url);
        file_put_contents('uploads/affiches/' . $affiche, file_get_contents($url));
    }
}