<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Repository\ArticlesRepository;
use App\Entity\Articles;
use Symfony\Component\HttpFoundation\Request;


class RestApiController extends AbstractController
{
    /**
     * @Route("/articles", name="liste", methods={"GET"})
     */
public function liste(ArticlesRepository $articlesRepo)
{
    $articles = $articlesRepo->apiFindAll();
    $encoder = [new JsonEncoder()];
    $normalizer = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizer, $encoder);
    $jsonContent = $serializer->serialize($articles, 'json', []);

    $response = new Response($jsonContent);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
}

/**
 * @Route("/article/{id}", name="article", methods={"GET"})
 */
public function getArticle(Articles $article)
{
    $encoder = [new JsonEncoder()];
    $normalizer = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizer, $encoder);
    $jsonContent = $serializer->serialize($article, 'json', [
        'circular_reference_handler' => function ($object) {
            return $object->getId();
        }
    ]);
    $response = new Response($jsonContent);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
}

/**
 * @Route("/article/add", name="ajout", methods={"POST"})
 */

public function addArticle(Request $request)
{
    // On vérifie si la requête est une requête Ajax
    if($request->isXmlHttpRequest()) {
   
        $article = new Articles();
        $donnees = json_decode($request->getContent());
        $article->setTitle($donnees->titre);
        $article->setBody($donnees->contenu);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($article);
        $entityManager->flush();

        return new Response('ok', 201);
    }
    return new Response('Failed', 404);
}

/**
 * @Route("/article/edit/{id}", name="edit", methods={"PUT"})
 */
public function editArticle(?Articles $article, Request $request)
{
    // On vérifie si la requête est une requête Ajax
    if($request->isXmlHttpRequest()) {
        $donnees = json_decode($request->getContent());
        $code = 200;
        if(!$article){
            $article = new Articles();
            $code = 201;
        }
        $article->setTitle($donnees->titre);
        $article->setBody($donnees->contenu);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($article);
        $entityManager->flush();
        return new Response('ok', $code);
    }
    return new Response('Failed', 404);
}

/**
 * @Route("/article/delete/{id}", name="supprime", methods={"DELETE"})
 */
public function removeArticle(Articles $article)
{
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($article);
    $entityManager->flush();
    return new Response('ok');
}
}
