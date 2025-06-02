<?php

namespace App\Controller;

use dump;
use DateTime;
use App\Entity\Chat;
use App\Entity\Citas;
use App\Entity\Cliente;
use App\Entity\Veterinario;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\DataTransformer\DataTransformerChain;

class TimetableController extends AbstractController
{   

    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    #[Route('/agenda', name: 'app_timetable')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        //Obtenemos usuario
        $user = $this->security->getUser();
        /*
        // Obtener mis datos de mis citas
        $citas = $entityManager->getRepository(Citas::class)->findBy([
            'cliente_id' => $cliente->getId()
                ]);
*/

        if ($user instanceof Cliente || $user instanceof Veterinario) {
            
            if ($user instanceof Cliente){
                $citas = $user->getCitas();

                $cliente = $this->entityManager->getRepository(Cliente::class)->findOneBy([
                    'mail' => $user->getUserIdentifier()
                    ]);

                $chats = $this->entityManager->getRepository(Chat::class)->findOneBy([
                    'cliente_id' => $cliente->getId()
                    ]); 
                }
            else if($user instanceof Veterinario){
                $citas = $user->getCitas();

                $cliente = $this->entityManager->getRepository(Veterinario::class)->findOneBy([
                    'mail' => $user->getUserIdentifier()
                    ]);

                $chats = $this->entityManager->getRepository(Chat::class)->findOneBy([
                    'cliente_id' => $cliente->getId()
                    ]); 
                }
                // Pasar los datos a la plantilla
                return $this->render('booking/timetable.html.twig', [
                    'citas' => $citas,
                    'chats' => $chats
                ]);
            }
        else{
                throw $this->createAccessDeniedException('No tienes acceso a esta página.');
            }

        return $this->render('booking/timetable.html.twig');
    }
 
    #[Route('/agenda/delete/{id}', name: 'app_delete_cita')]
    public function deleteMascota(Citas $citas): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Cliente || $citas->getClienteId() !== $user) {
            throw $this->createAccessDeniedException('No tienes acceso a esta página.');
        }

        $this->entityManager->remove($citas);
        $this->entityManager->flush();

        $this->addFlash('success', 'Cita eliminada con éxito.');
        return $this->redirectToRoute('app_timetable');
    }



}