<?php

namespace App\Controller;

use dump;
use DateTime;
use App\Entity\Mascotas;
use App\Entity\Historial;
use App\Entity\Veterinario;
use Doctrine\ORM\EntityManager;
use App\Repository\HistorialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\History;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\DataTransformer\DataTransformerChain;

class ServiceController extends AbstractController
{   

    private $entityManager;

    // El constructor recibe el EntityManager y lo asigna a la propiedad de la clase
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/consultas', name: 'app_service_consultation')]
    public function serviceInfo(): Response
    {   
        return $this->render('services/simple/consulta.html.twig');
    }

    #[Route('/otros_servicios', name: 'app_service_other')]
    public function serviceOther(): Response
    {   
        return $this->render('services/simple/otherServices.html.twig');
    }

    #[Route('/urgencias', name: 'app_emergencies')]
    public function emergencies(): Response
    {   
        return $this->render('services/simple/emergencies.html.twig');
    }
        


}
