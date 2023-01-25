<?php

namespace App\Controller;

use App\Form\FilmSearchFormType;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


#[Route('/tools')]
class ToolsController extends AbstractController
{

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    #[Route('/test/{titre}', name: 'app_tools_test')]
    public function test(?string $titre): RedirectResponse
    {
        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/search/movie?api_key=c7924bfc3e4208e9e6eafb5beaee9940&query=' . $titre . '&language=fr'
        );
        $temp = json_decode($response->getContent(), true);
        $result = $temp['results'][0];
        $id = $result['id'];

        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/movie/' . $id . '?api_key=c7924bfc3e4208e9e6eafb5beaee9940&language=fr'
        );

        $film = json_decode($response->getContent(), true);
        $affiche = $film['poster_path'];

        $response = $this->client->request(
            'GET',
            'https://image.tmdb.org/t/p/w500/' . $affiche
        );

        // Initialize a file URL to the variable
        $url =
            'https://image.tmdb.org/t/p/w500/' . $affiche;

        // Use basename() function to return the base name of file
        $file_name = basename($url);

        if (file_put_contents('uploads/affiches/' . $file_name, file_get_contents($url))) {
            echo "File downloaded successfully";
        } else {
            echo "File downloading failed.";
        }

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/recup/{id}', name: 'tools_recup')]
    public function recuperation(FilmRepository $repository, int $id = 1): RedirectResponse|Response
    {
        $film = $repository->find($id);

        $code = $this->getCode($film->getTitre());

        if ($code <> 0) {
            $infos = $this->getInfo($code);

//        dd($infos);
            $data['code'] = $code;
            $data['originalTitle'] = $infos['originalTitle'];
            $data['productionYear'] = $infos['productionYear'];
            $data['title'] = $infos['title'];
            $data['synopsis'] = $infos['synopsis'];
            $data['directors'] = $infos['castingShort']['directors'];
            $data['actors'] = $infos['castingShort']['actors'];
            $data['poster'] = $infos['poster']['href'];

            $this->download_image($data['poster'], 'images/jaquettes/' . $code . '.jpg');
            if (isset($infos['nationality'])) {
                $nb = sizeof($infos['nationality']);
                for ($i = 0; $i < $nb; $i++) {
                    $nationality[$i] = $infos['nationality'][$i]['$'];
                }
                $data['nationalites'] = $nationality;
            }

            if (isset($infos['genre'])) {
                $nb = sizeof($infos['genre']);
                for ($i = 0; $i < $nb; $i++) {
                    $genres[$i] = $infos['genre'][$i]['$'];
                }
                $data['genres'] = $genres;
            }

            return $this->render('films/api.html.twig', compact('data'));
        } else {
            $this->addFlash('error', 'Film non trouvé');
            return $this->redirectToRoute('film_index');
        }
    }

    #[Route('/maj', name: 'tools_maj')]
    public function maj(FilmRepository $repository): RedirectResponse
    {
        $films = $repository->findAll();
        $i = 0;
        foreach ($films as $film) {
            $i++;
            $code = $this->getCode($film->getTitre());
            if ($code <> 0) {
                dump($this->getInfo($code));
            }
            if ($i == 10) {
                break;
            }
        }
        $this->addFlash('success', 'mise à jour effectuée');
        return $this->redirectToRoute('film_index');
    }

    #[Route('/catalogue', name: 'app_tools_catalogue')]
    public function catalogue(): RedirectResponse
    {
        $process = new Process(['python3', '/Users/gilles/Documents/INFORMATIQUE/CODES_SOURCES/Python/Outils/_catalogue_maxtor.py']);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $this->addFlash('danger', 'Il y a eu un problème');
            throw new ProcessFailedException($process);
        } else {
            $this->addFlash('success', 'Catalogue téléchargé');
        }
        return $this->redirectToRoute('app_admin');
    }

    // takes URL of image and Path for the image as parameter
    public function download_image($url, $path)
    {
        $newfname = $path;
        $file = fopen($url, 'rb');
        if ($file) {
            $newf = fopen($newfname, 'wb');
            if ($newf) {
                while (!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                }
            }
        }
        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
        }
    }
}
