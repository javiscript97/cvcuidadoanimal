<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Citas;
use App\Entity\Cliente;
use App\Entity\Veterinario;
use App\Controller\BookFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BookAppController extends AbstractController
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[Route('/reservar-cita', name: 'app_book')]
    public function nuevaCita(Request $request): Response
    {
        $cita = new Citas();
        $form = $this->createForm(BookFormType::class, $cita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // Obtener el cliente actual de la sesión
            $cliente = $this->security->getUser();
            $cita->setClienteId($cliente);

            // Obtener un veterinario aleatorio
            $veterinarios = $this->entityManager->getRepository(Veterinario::class)->findAll();
            $selectedVet = $veterinarios[array_rand($veterinarios)];
            $cita->setVetId($selectedVet);

            // Verificar que el veterinario no esté ocupado en la fecha seleccionada
            $fecha = $cita->getFecha();
            $ocupado = $this->entityManager->getRepository(Citas::class)->findOneBy([
                'fecha' => $fecha,
                'vet_id' => $selectedVet,
            ]);

            if ($ocupado) {
                $this->addFlash('error', 'El veterinario ya tiene una cita en esta fecha. Por favor, elige otra fecha.');
                return $this->redirectToRoute('nueva_cita');
            }
            $citaChat = $form->getData();
            //Creamos chat si hemos seleccionado consulta en linea
            if($citaChat->getTipo() == "consulta_online"){
                
                $chat = new Chat();
                $clienteActual = $this->entityManager->getRepository(Cliente::class)->findOneBy([
                    'mail' => $cliente->getUserIdentifier()
                ]);
                
                $chat->setClienteId($clienteActual);
                $chat->setVetId($selectedVet);
                $chat->setContenido("...");
                $chat->setFecha(new \DateTime());
                $this->entityManager->persist($chat);
                $this->entityManager->flush();
            }

            $this->entityManager->persist($cita);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('booking/booking_app.html.twig', [
            'reservaCitaForm' => $form->createView(),
        ]);
    }
}
