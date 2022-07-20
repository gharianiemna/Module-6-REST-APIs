<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="app_articles")
     */
    public function index(): Response
    {
    $client = new Client(['base_uri' => 'https://jsonplaceholder.typicode.com/']);
    $response = $client->request('GET', '/posts');
    $body = $response->getBody();
    $articles = json_decode($body);

        return $this->render('articles/index.html.twig', [
            'controller_name' => 'ArticlesController',
                'articles' => $articles
        ]);
    }
}
