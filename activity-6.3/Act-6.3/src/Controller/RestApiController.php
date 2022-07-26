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
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use  Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

/**
 * Class RestApiController
 * @package App\Controller
 * /**
 * @Rest\Route("/api/article")
 */

class RestApiController extends AbstractController
{
    /**
     * @Get("/", name="liste")
     */
    public function liste(ArticlesRepository $articlesRepo)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $articles = $articlesRepo->findAll();
        $serializer = new Serializer(array(new DateTimeNormalizer('d.m.Y'), new GetSetMethodNormalizer($classMetadataFactory)), array('json' => new JsonEncoder()));
        $data = $serializer->serialize($articles, 'json',['groups' => 'art']);
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
    * @Get( path = "/{id}", name = "app_article_show", requirements = {"id"="\d+"} )
    */
    public function getArticle(Articles $article, ArticlesRepository $articlesRepo, $id)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $article = $articlesRepo->find($id);
       if(!$article){
            return $this->json(["error message" => "article not found"],200);
        }
           $serializer = new Serializer(array(new DateTimeNormalizer('d.m.Y'), new GetSetMethodNormalizer($classMetadataFactory)), array('json' => new JsonEncoder()));
        $data = $serializer->serialize($article, 'json',['groups' => 'art']);
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
/**
 * @GET("/lastThree", name="derniers")
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
     * @Post("/", name="ajout")
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
     * @Put("/{id}", name="edit")
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
     * @Delete("/{id}", name="supprime")
     */
    public function removeArticle(Articles $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        return new Response('Success');
    }
}
