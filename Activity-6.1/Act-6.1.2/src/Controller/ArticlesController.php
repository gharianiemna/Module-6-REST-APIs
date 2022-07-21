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
     * @Route("/get", name="GET")
     */
    public function GetPosts(): Response
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1:8000/']);
        $response = $client->request('GET', '/articles');
        $body = $response->getBody();
        $articles = json_decode($body);

        $response = $client->request('GET', '/comments');
        $body = $response->getBody();
        $comments = json_decode($body);

            return $this->render('articles/index.html.twig', [
                'controller_name' => 'ArticlesController',
                    'articles' => $articles,
                    'commentaires'=>$comments
            ]);
    }

     /**
     * @Route("/post", name="POST")
     */
    public function postPost(): Response
    {   $client = new Client();
        $response = $client->request('POST', 'https://jsonplaceholder.typicode.com/posts', [
            'form_params' => [
                'userId' => '1',
                'id' => '200',
                'title' => 'Test title',
                'body' => 'test body',
                ]
        ]);
        $a=$response->getBody();
        $code=$response->getStatusCode() ;
        $reason = $response->getReasonPhrase(); // OK
            return $this->render('articles/message.html.twig', [
                    'controller_name' => 'ArticlesController',
                    'code'=>$code ,
                    'a'=>$a,
                    'reason'=>$reason
            ]);
    }   

     /**
     * @Route("/put", name="PUT")
     */
    public function putPost()
    {
        $client = new Client();
        $response=$client->put('https://jsonplaceholder.typicode.com/posts/1', [
            'form_params' => [
                    'userId' => '1',
                'id' => '300',
                'title' => 'put test title',
                'body' => 'test body',
            ],
            'timeout' => 5
        ]);
        $a=$response->getBody();
        $code=$response->getStatusCode() ;
        $reason = $response->getReasonPhrase(); // OK
            return $this->render('articles/message.html.twig', [
                    'controller_name' => 'ArticlesController',
                    'code'=>$code ,
                    'a'=>$a,
                    'reason'=>$reason
            ]);
    }

     /**
     * @Route("/delete", name="DELETE")
     */
    public function deletePost()
    {
        $client = new Client();
        $response =$client->delete('https://jsonplaceholder.typicode.com/posts/1');
        $code=$response->getStatusCode() ;
        $reason = $response->getReasonPhrase(); // OK
            return $this->render('articles/message.html.twig', [
                    'controller_name' => 'ArticlesController',
                    'code'=>$code ,
                    'reason'=>$reason,
                    'a'=>''
            ]);
    }
    //         /**
    //  * @Route("/post/{id}", name="getId")
    //  */
    // public function GetPostById($id): Response
    // {
    // $client = new Client(['base_uri' => 'https://jsonplaceholder.typicode.com/']);
    // $response = $client->request('GET', '/posts/'.$id);
    // $body = $response->getBody();
    // $articles = json_decode($body);

    //     return $this->render('articles/index.html.twig', [
    //         'controller_name' => 'ArticlesController',
    //             'articles' => $articles
    //     ]);
    // }
}


