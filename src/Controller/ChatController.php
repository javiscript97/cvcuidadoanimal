<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Cliente;
use App\Entity\Veterinario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat/{id}', name: 'app_chat_show')]
    public function showChat(int $id, EntityManagerInterface $em): Response
    {
        // Cambia el método findBy por find para obtener un solo chat
        $chat = $em->getRepository(Chat::class)->find($id);

        if (!$chat) {
            throw $this->createNotFoundException('Chat no encontrado');
        }

        // Obtener todos los mensajes relacionados con el chat
        $messages = $em->getRepository(Chat::class)->findBy(['id' => $id]);

        return $this->render('chat/chat.html.twig', [
            'chat' => $chat,     // Deberías pasar el objeto chat en lugar de un array
            'messages' => $messages, // Asegúrate de que tu vista tenga acceso a los mensajes
        ]);
    }

    #[Route('/chat/{id}/send', name: 'app_chat_send', methods: ['POST'])]
public function sendMessage(int $id, Request $request, EntityManagerInterface $em): Response
{
    $chat = $em->getRepository(Chat::class)->find($id);
    
    if (!$chat) {
        throw $this->createNotFoundException('Chat no encontrado');
    }

    $nuevoMensaje = $request->request->get('message');
    // Concatenar el nuevo mensaje al contenido existente
    $contenidoActual = $chat->getContenido();
    $chat->setContenido($contenidoActual . "\n" . $nuevoMensaje); // Añade el nuevo mensaje

    $em->persist($chat);
    $em->flush();

    // Redirige a la misma página para que se muestre el nuevo mensaje
    return $this->redirectToRoute('app_chat_show', ['id' => $id]);
}

#[Route('/chat', name: 'app_chat_show')]
public function manageChatt(int $id, EntityManagerInterface $em): Response
{
    // Cambia el método findBy por find para obtener un solo chat
    $chat = $em->getRepository(Chat::class)->find($id);

    if (!$chat) {
        throw $this->createNotFoundException('Chat no encontrado');
    }

    // Obtener todos los mensajes relacionados con el chat
    $messages = $em->getRepository(Chat::class)->findBy(['id' => $id]);

    return $this->render('chat/chat.html.twig', [
        'chat' => $chat,     // Deberías pasar el objeto chat en lugar de un array
        'messages' => $messages, // Asegúrate de que tu vista tenga acceso a los mensajes
    ]);
}

}
