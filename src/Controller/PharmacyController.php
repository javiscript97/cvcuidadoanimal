<?php

namespace App\Controller;

use dump;
use DateTime;
use App\Entity\Producto;
use Doctrine\ORM\EntityManager;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\DataTransformer\DataTransformerChain;

class PharmacyController extends AbstractController
{   

    private $entityManager;

    // El constructor recibe el EntityManager y lo asigna a la propiedad de la clase
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/farmacia', name: 'app_pharmacy')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Obtener los datos de la tabla farmacia
        $productos = $entityManager->getRepository(Producto::class)->findAll();

        // Pasar los datos a la plantilla
        return $this->render('services/complete/pharmacy.html.twig', [
            'productos' => $productos,
        ]);
    }
 




}