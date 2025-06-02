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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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

        
    #[Route('/farmacia/add-producto', name: 'app_producto_add')]
    public function add(Request $request): Response
    {
        $prod = new Producto();

        $form = $this->createFormBuilder($prod)
        ->add('nombre', TextType::class, [
            'label' => 'Nombre',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2'
            ]
        ])
        ->add('descripcion', TextType::class, [
            'label' => 'Descripción',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2'
            ]
        ])
        ->add('stock', TextType::class, [
            'label' => 'Stock',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2'
            ]
        ])
        ->add('precio', TextType::class, [
            'label' => 'Precio',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2'
            ]
        ])
        ->add('caducidad', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Caducidad',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
        ])
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $this->entityManager->persist($prod);
            $this->entityManager->flush();

            $this->addFlash('success', 'Producto añadido con éxito.');

            return $this->redirectToRoute('app_pharmacy');
        }

        return $this->render('cpanel/add_productos.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/farmacia/edit-producto/{id}', name: 'app_producto_edit')]
    public function editProducto(Request $request,  Producto $prod): Response
    {
        $form = $this->createFormBuilder($prod)
        ->add('nombre', TextType::class, [
            'label' => 'Nombre',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2'
            ]
        ])
        ->add('descripcion', TextType::class, [
            'label' => 'Descripción',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2'
            ]
        ])
        ->add('stock', TextType::class, [
            'label' => 'Stock',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2'
            ]
        ])
        ->add('precio', NumberType::class, [
            'label' => 'Precio',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2',
                'step' => '0.01'
            ]
        ])
        ->add('caducidad', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Caducidad',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
        ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->entityManager->flush();

            $this->addFlash('success', 'Producto añadida con éxito.');
            return $this->redirectToRoute('app_pharmacy');
        }

        return $this->render('cpanel/add_productos.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/farmacia/delete-producto/{id}', name: 'app_producto_delete')]
    public function deleteProducto(Producto $prod): Response
    {

        $this->entityManager->remove($prod);
        $this->entityManager->flush();

        $this->addFlash('success', 'Producto eliminado con éxito.');
        return $this->redirectToRoute('app_pharmacy');
    }

 


}