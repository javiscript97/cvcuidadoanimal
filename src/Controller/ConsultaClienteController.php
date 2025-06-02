<?php
namespace App\Controller;

use App\Repository\ChatRepository;
use App\Repository\CitasRepository;
use App\Repository\VeterinarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Chat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ConsultaClienteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mis-consultas', name: 'app_consultas_user')]
    #[IsGranted('ROLE_USER')]
    public function listaChats(CitasRepository $citasRepository): Response
    {
        $cliente = $this->getUser();

        $citas = $citasRepository->findBy([
            'tipo' => 'consulta_online',
            'cliente_id' => $cliente,
        ]);

        $veterinarios = [];
        
        foreach ($citas as $cita) {
            $vet = $cita->getVetId();
            if (!in_array($vet, $veterinarios, true)) {
                $veterinarios[] = $vet;
            }
        }

        return $this->render('chat/consultas_clientes.html.twig', [
            'vets' => $veterinarios,
        ]);
    }

    #[Route('/mis-consultas/{id}', name: 'app_chat_user')]
    #[IsGranted('ROLE_USER')]
    public function detalleChat(int $id, VeterinarioRepository $veterinarioRepository, Request $request, ChatRepository $chatRepository): Response
    {
        $vet = $veterinarioRepository->find($id);
        $cliente = $this->getUser();

        $existeConsulta = $chatRepository->findOneBy([
            'cliente_id' => $cliente,
                'vet_id' => $vet,
        ]);

        if (!$existeConsulta) {
            $this->addFlash('warning', 'La consulta aún no ha sido iniciada por el veterinario.');
            return $this->redirectToRoute('app_consultas_user');
        }

        if (!$vet) {
            throw $this->createNotFoundException('Veterinario no encontrado');
        }

        $mensajes = $chatRepository->findBy([
            'cliente_id' => $cliente,
            'vet_id' => $id
        ], ['fecha' => 'ASC']);

       if ($request->isMethod('POST')) {
            $contenido = $request->request->get('mensaje');
            if ($contenido) {
                $chat = new Chat();
                $chat->setClienteId($cliente);
                $chat->setVetId($vet);
                $chat->setContenido($contenido);
                $chat->setFecha(new \DateTime());

                 $this->entityManager->persist($chat);
                 $this->entityManager->flush();

                return $this->redirectToRoute('app_chat_user', ['id' => $id]);
            }
        }

            return $this->render('chat/chat_cliente.html.twig', [
                'vet' => $vet,
                'mensajes' => $mensajes,
            ]);
    }
}
