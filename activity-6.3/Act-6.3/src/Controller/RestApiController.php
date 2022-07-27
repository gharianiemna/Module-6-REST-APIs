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
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;




class RestApiController extends AbstractController
{
         /**
     * @Rest\View(serializerGroups={"articles"})
     * @Rest\Get("/articles")
     * @param ArticlesRepository $articlesRepo
     * @return Articles[]
     */
    public function fetchList(ArticlesRepository $articlesRepo)
    {
        return $articlesRepo->findAll();
    }


     /**
     * @Rest\View(serializerGroups={"articles"})
     * @Rest\Get("/api/articles")
     * @param ArticlesRepository $articlesRepo
     * @return Articles[]
     */
    public function secureFetchList(ArticlesRepository $articlesRepo)
    {
        return $articlesRepo->findAll();
    }

 /**
     * @Rest\Get("/api/article/{id}")
    * @Rest\View(serializerGroups={"articlesById"})
    * @param ArticlesRepository $articlesRepo
     * @return Articles[]
     * @return object
     */
    public function fetchById(Articles $Article ,ArticlesRepository $articlesRepo)
    {
       return  $articlesRepo->find($Article);
    }


  /**
     * @Rest\Get("/api/article/lastThree")
     * @Rest\View(serializerGroups={"articles"})
     * @param ArticlesRepository $articlesRepo
     * @return Articles[]
     * @return object
     */
    public function LastThree(ArticlesRepository $articlesRepo)
    {
        return   $articlesRepo->findBylast();

    }

 /**
     * @Rest\View(statusCode = 201, serializerGroups={"articles"})
     * @Rest\Post("/api/article", name="PostArticle")
     */
    public function addAricle(EntityManagerInterface $em,Request $request,SerializerInterface $serializer)
    {
        try {
            $article = $serializer->deserialize($request->getContent(),Articles::class,'json');
            $em->persist($article);
            $em->flush();
            return $article;
        } catch (NotEncodableValueException $e) {
            return $this->json(["error message"=>$e->getMessage()],400);
        }
    }

    /**
     * @Put("/api/article/{id}", name="edit")
     */
    public function editArticle(?Articles $article, Request $request)
    {
            $donnees = json_decode($request->getContent());
            $code = 200;
            if(!$article){
                $code = 404;
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
     */
    public function removeArticle(Articles $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        return new Response('Success');
    }
}
