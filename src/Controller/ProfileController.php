<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Usuario;
use App\Entity\Mascotas;
use App\Entity\Veterinario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        // Obtener el cliente actual (suponiendo que ya está autenticado)
        $user = $this->getUser();
/*
        if (!$user instanceof Cliente || !$user instanceof Veterinario) {
            throw $this->createAccessDeniedException('No tienes acceso a esta página.');
        }*/
        if($user instanceof Cliente){
                // Obtener las mascotas del cliente
                $mascotas = $user->getMascotas();

                return $this->render('profile/profile.html.twig', [
                    'user' => $user,
                    'mascotas' => $mascotas,
                ]);
        }
        else{
                return $this->render('profile/profile_vet.html.twig', [
                    'user' => $user,
                    ]);
            }
    }

    #[Route('/profile/mascota/add', name: 'app_profile_add_mascota')]
    public function addMascota(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Cliente) {
            throw $this->createAccessDeniedException('No tienes acceso a esta página.');
        }

        $mascota = new Mascotas();
        $form = $this->createFormBuilder($mascota)
            ->add('nombre', TextType::class, ['label' => 'Nombre',
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
             ]
            ])
            ->add('edad', TextType::class, ['label' => 'Edad',
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
             ]
            ])
            ->add('raza', TextType::class, ['label' => 'Raza',
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
             ]
            ])
            ->add('animal', TextType::class, ['label' => 'Animal',
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
             ]
            ])
            ->add('genero', TextType::class, ['label' => 'Género',
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
             ]
            ])
            ->add('ficha', TextType::class, ['label' => 'Enfermedades',
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
             ]
            ])             
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mascota->setClienteId($user);
            $this->entityManager->persist($mascota);
            $this->entityManager->flush();

            $this->addFlash('success', 'Mascota añadida con éxito.');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/addProfile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();
   
        if($user instanceof Cliente){

                $form = $this->createFormBuilder($user)
                    ->add('nombre', TextType::class, ['label' => 'Nombre',
                    'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                    ]
                    ])
                    ->add('edad', TextType::class, ['label' => 'Edad',
                    'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                    ]
                    ])  
                    ->add('direccion', TextType::class, ['label' => 'Dirección',
                    'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                    ]
                    ])  
                    ->add('mail', EmailType::class, ['label' => 'Email',
                    'constraints' => [
                    new NotBlank(['message' => 'El correo no puede estar vacío']),
                    new Email(['message' => 'Introduce un correo electrónico válido']),
                    ],
                    'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                    ]
                    ])  
                    ->add('telefono', TextType::class, ['label' => 'Teléfono',
                    'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                    ]
                    ])  
                    ->getForm();
        }else{
                    $form = $this->createFormBuilder($user)
                    ->add('nombre', TextType::class, ['label' => 'Nombre',
                        'attr' => [
                        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                        ]
                    ])
                    ->add('edad', TextType::class, ['label' => 'Edad',
                        'attr' => [
                        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                        ]
                    ])  
                    ->add('mail', TextType::class, ['label' => 'Mail',
                        'attr' => [
                        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                        ]
                    ])  
                    ->add('telefono', TextType::class, ['label' => 'Teléfono',
                        'attr' => [
                        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                    ]
                        ])  
                        ->getForm();
                }

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                $this->addFlash('success', 'Perfil actualizado con éxito.');
                return $this->redirectToRoute('app_profile');
            }
        if($user instanceof Cliente){    
            return $this->render('profile/editTheProfile.html.twig', [
                'form' => $form->createView(),
            ]);
        }else{
            return $this->render('profile/editTheVetProfile.html.twig', [
                'form' => $form->createView(),
            ]);
        }
       
    }

    #[Route('/profile/delete', name: 'app_profile_delete')]
    public function deleteProfile(): Response
    {
        $user = $this->getUser();

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'Cuenta eliminada con éxito.');
        return $this->redirectToRoute('app_home'); 
    }
    #[Route('/profile/mascota/edit/{id}', name: 'app_profile_edit_mascota')]
    public function editMascota(Request $request, Mascotas $mascota): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Cliente || $mascota->getClienteId() !== $user) {
            throw $this->createAccessDeniedException('No tienes acceso a esta página.');
        }

        $form = $this->createFormBuilder($mascota)
        ->add('nombre', TextType::class, ['label' => 'Nombre',
        'attr' => [
        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
         ]
        ])
        ->add('edad', TextType::class, ['label' => 'Edad',
        'attr' => [
        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
         ]
        ])
        ->add('raza', TextType::class, ['label' => 'Raza',
        'attr' => [
        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
         ]
        ])
        ->add('animal', TextType::class, ['label' => 'Animal',
        'attr' => [
        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
         ]
        ])
        ->add('genero', TextType::class, ['label' => 'Género',
        'attr' => [
        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
         ]
        ])
        ->add('ficha', TextType::class, ['label' => 'Enfermedades',
        'attr' => [
        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
         ]
        ])       
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Mascota modificada con éxito.');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/editingProfile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/mascota/delete/{id}', name: 'app_profile_delete_mascota')]
    public function deleteMascota(Mascotas $mascota): Response
    {
        $user = $this->getUser();

        $this->entityManager->remove($mascota);
        $this->entityManager->flush();

        $this->addFlash('success', 'Mascota eliminada con éxito.');
        return $this->redirectToRoute('app_profile');
    }
}
