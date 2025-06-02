<?php
// src/Controller/ConsultaOnlineController.php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Cliente;
use App\Entity\Veterinario;
use App\Repository\ClienteRepository;
use App\Repository\ChatRepository;
use App\Repository\CitasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ConsultaOnlineController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/vet/consultas', name: 'app_consultas_vet')]
    #[IsGranted('ROLE_VET')]
    public function index(CitasRepository $citasRepository): Response
    {
        $vet = $this->getUser();

        $citas = $citasRepository->findBy([
            'tipo' => 'consulta_online',
            'vet_id' => $vet,
        ]);

        $clientes = [];
        
        foreach ($citas as $cita) {
            $cliente = $cita->getClienteId();
            if (!in_array($cliente, $clientes, true)) {
                $clientes[] = $cliente;
            }
        }

        return $this->render('chat/consultas.html.twig', [
            'clientes' => $clientes,
        ]);
    }

    #[Route('/vet/consultas/chat/{id}', name: 'app_chat_vet')]
    #[IsGranted('ROLE_VET')]
    public function chat(int $id, ClienteRepository $clienteRepository, ChatRepository $chatRepository, Request $request): Response
    {
        $cliente = $clienteRepository->find($id);
        $veterinario = $this->getUser();

        if (!$cliente) {
            throw $this->createNotFoundException('Cliente no encontrado');
        }

        if ($request->isMethod('POST')) {
            $contenido = $request->request->get('mensaje');
            if ($contenido) {
                $chat = new Chat();
                $chat->setClienteId($cliente);
                $chat->setVetId($veterinario);
                $chat->setContenido($contenido);
                $chat->setFecha(new \DateTime());

                 $this->entityManager->persist($chat);
                 $this->entityManager->flush();

                return $this->redirectToRoute('app_chat_vet', ['id' => $id]);
            }
        }

        $mensajes = $chatRepository->findBy([
            'cliente_id' => $cliente,
            'vet_id' => $veterinario
        ], ['fecha' => 'ASC']);

        return $this->render('chat/chat.html.twig', [
            'cliente_id' => $cliente,
            'mensajes' => $mensajes,
        ]);
    }
}
