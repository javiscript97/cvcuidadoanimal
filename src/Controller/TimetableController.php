<?php

namespace App\Controller;

use dump;
use DateTime;
use App\Entity\Citas;
use App\Entity\Producto;
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
        $cliente = $this->security->getUser();
        // Obtener mis datos de mis citas
        $citas = $entityManager->getRepository(Citas::class)->findAll();

        // Pasar los datos a la plantilla
        return $this->render('booking/timetable.html.twig', [
            'citas' => $citas,
        ]);
    }
 




}