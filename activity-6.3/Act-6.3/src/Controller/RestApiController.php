<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use App\Repository\ArticlesRepository;
use App\Entity\Articles;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use  Doctrine\ORM\EntityManagerInterface;



class RestApiController extends AbstractController
{
    /**
     * @Get("/articles", name="liste")
     * @IsGranted("ROLE_USER")
     */
    public function liste(ArticlesRepository $articlesRepo)
    {
        $articles = $articlesRepo->findAll();
        $serializer = new Serializer(array(new DateTimeNormalizer('d.m.Y'), new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $data = $serializer->serialize($articles, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
    * @Get( path = "/article/{id}", name = "app_article_show", requirements = {"id"="\d+"} )
    * @IsGranted("ROLE_USER")
    */
    public function getArticle(Articles $article, ArticlesRepository $articlesRepo, $id)
    {
        $article = $articlesRepo->find($id);
        $serializer = new Serializer(array(new DateTimeNormalizer('d.m.Y'), new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $data = $serializer->serialize($article, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
/**
 * @GET("/article/lastThree", name="derniers")
 * @IsGranted("ROLE_USER")
 */
public function listeById(ArticlesRepository $articlesRepo)
{
    $articles = $articlesRepo->findBylast();
    $serializer = new Serializer(array(new DateTimeNormalizer('d.m.Y'), new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
    $data = $serializer->serialize($articles, 'json');
    $response = new Response($data);
    $response->headers->set('Content-Type', 'application/json');
    return $response;

}
    /**
     * @Post("/api/article", name="ajout")
     * @IsGranted("ROLE_ADMIN")
     */

    public function addArticle(Request $request, ManagerRegistry $doctrine)
    {  
            $article = new Articles();
            $donnees = json_decode($request->getContent());
            $article->setTitle($donnees->title)
                    ->setBody($donnees->body)
                    ->setAuthor($donnees->author)
                    ->setDate(new \DateTime());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->json($article,200,[]);
    }

    /**
     * @Put("/api/article/{id}", name="edit")
     *  @IsGranted("ROLE_ADMIN")
     */
    public function editArticle(?Articles $article, Request $request)
    {
            $donnees = json_decode($request->getContent());
            $code = 200;
            if(!$article){
                $article = new Articles();
                $code = 201;
            }
            $article->setTitle($donnees->title);
            $article->setBody($donnees->body);
            $article->setAuthor($donnees->author);
            $article->setDate (new \DateTime((string)$donnees->date));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
           return $this->json($article,$code,[]);
    }

    /**
     * @Delete("/api/article/{id}", name="supprime")
     *  @IsGranted("ROLE_ADMIN")
     */
    public function removeArticle(Articles $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        return new Response('Success');
    }
}
