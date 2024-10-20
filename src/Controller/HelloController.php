<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{
    private array $messages = [
        ['message' => 'Hello', 'created' => '2024/05/12'],
        ['message' => 'Hi', 'created' => '2024/05/12'],
        ['message' => 'Ey', 'created' => '2023/05/12']
    ];

    #[Route('/msg/{limit<\d+>?3}', name: 'app_index')] //Varios parametros, con un limite de 3
    public function index(int $limit) : Response
    {
        //return new Response(); //Se van añadiendo al enseñarlo en la respuesta
        return $this->render(
            'history_post/index.html.twig',
            //opcional:
            [
                'messages' => $this->messages,
                'limit' => $limit
            ]
        );
    }   
    #[Route('/messages/{id<\d+>}', name: 'app_show_one')]
    public function showOne($id) : Response
    {
        return $this->render(
            'hello/show_one.html.twig',
            //opcional:
            [
                'message' => $this->messages[$id]
            ]
        );
        //return new Response( $this->messages[$id] );

    }  

}