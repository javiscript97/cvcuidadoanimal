<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Citas;
use App\Entity\Cliente;
use App\Entity\Mascotas;
use App\Entity\Producto;
use App\Entity\Veterinario;
use App\Entity\Administrador;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PanelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Nuestro Panel de Control
    #[Route('/panel-control', name: 'app_panel')]
    public function index( AuthenticationUtils $utils): Response
    {

        return $this->render('cpanel/cpanel.html.twig');

    }

    // Añadimos un cliente
    #[Route('/panel-control/add-cliente', name: 'app_cpanel_cl_add')]
    public function addCliente(Request $request,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $cliente = new Cliente();
        $form = $this->createFormBuilder($cliente)
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
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
            ])
            ->add('telefono', TextType::class, ['label' => 'Teléfono',
                'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
            ])
            ->add('rol', TextType::class, ['label' => 'Rol',
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => ['label' => 'Contraseña',
                    'attr' => [
                        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                        ]],
                'second_options' => ['label' => 'Confirmar Contraseña',
                    'attr' => [
                        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                        ]
                        ],
                'invalid_message' => 'Las contraseñas deben de ser la misma',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingUser = $this->entityManager->getRepository(Cliente::class)->findOneBy(['mail' => $cliente->getMail()]);

            if ($existingUser) {
                $this->addFlash('error', 'El correo electrónico ya está registrado.');
                return $this->redirectToRoute('app_cpanel_cl_add');
            }


            $cliente->setPassword(
                $passwordHasher->hashPassword(
                    $cliente,
                    $form->get('plainPassword')->getData()
                )
            );
   /*         
            $data = $form->getData();
            
            if($data->getRol() == "ROLE_VET"){
                $vet = new Veterinario();
                $vet->setNombre($data->getNombre());
                $vet->setEdad($data->getEdad());
                $vet->setMail($data->getMail());
                $vet->setRol($data->getRol());
                $vet->setEspecialidad('veterinario');

                $vet->setPassword(
                    $passwordHasher->hashPassword(
                        $vet,
                        $form->get('plainPassword')->getData()
                    )
                );
                
                $this->entityManager->persist($vet);
                $this->entityManager->flush();

            }
            else if($data->getRol()== "ROLE_ADMIN"){
                $ad = new Administrador();
                $ad->setNombre($data->getNombre());
                $ad->setMail($data->getMail());
                $ad->setRol($data->getRol());

                $ad->setPassword(
                    $passwordHasher->hashPassword(
                        $ad,
                        $form->get('plainPassword')->getData()
                    )
                );
                $this->entityManager->persist($ad);
                $this->entityManager->flush();
            }*/


            $this->entityManager->persist($cliente);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_cpanel_cl_show');
        }

        return $this->render('cpanel/add_clientes.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/panel-control/edit-cliente/{id}', name: 'app_cpanel_cl_edit')]
    public function editCliente(Request $request, Cliente $cliente): Response
    {

        $form = $this->createFormBuilder($cliente)
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
            ->add('rol', TextType::class, ['label' => 'Rol',
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
            ])  
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          /*  $data = $form->getData();
            if($data->getRol() == "ROLE_VET"){
                $vet = new Veterinario();
                $vet->setNombre($data->getNombre());
                $vet->setEdad($data->getEdad());
                $vet->setMail($data->getMail());
                $vet->setRol($data->getRol());
                $vet->setEspecialidad('veterinario');
                $this->entityManager->persist($vet);
                $this->entityManager->flush();
            }*/

            $this->entityManager->flush();

            $this->addFlash('success', 'Perfil actualizado con éxito.');
            return $this->redirectToRoute('app_cpanel_cl_show');
        }

        return $this->render('cpanel/edit_clientes.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/panel-control/delete-cliente/{id}', name: 'app_cpanel_cl_delete')]
    public function deleteCliente(Cliente $cliente): Response
    {

        $this->entityManager->remove($cliente);
        $this->entityManager->flush();

        $this->addFlash('success', 'Cliente eliminado con éxito.');
        return $this->redirectToRoute('app_cpanel_cl_show');
    }

    #[Route('/panel-control/mostrar-clientes', name: 'app_cpanel_cl_show')]
    public function showCliente(EntityManagerInterface $entityManager): Response
    {
        $clientes = $entityManager->getRepository(Cliente::class)->findAll();

        return $this->render('cpanel/cpanel_clientes.html.twig', [
            'clientes' => $clientes,
        ]);
    }

        
    #[Route('/panel-control/add-mascota', name: 'app_cpanel_mascota_add')]
    public function addMascota(Request $request): Response
    {
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
             ->add('cliente_id', EntityType::class, [
                'class' => Cliente::class,
                'choice_label' => 'mail',
                'label' => 'Correo del Cliente',
                'placeholder' => 'Selecciona un cliente',
                'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                ]
            ])    
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

        $clienteSeleccionado = $form->get('cliente_id')->getData();
        $mascota->setClienteId($clienteSeleccionado);

            $this->entityManager->persist($mascota);
            $this->entityManager->flush();

            $this->addFlash('success', 'Mascota añadida con éxito.');

            return $this->redirectToRoute('app_cpanel_mascota_show');
        }

        return $this->render('cpanel/add_mascotas.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/panel-control/edit-mascota/{id}', name: 'app_cpanel_mascota_edit')]
    public function editMascota(Request $request,  Mascotas $mascota): Response
    {
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

           /* $clienteId = $form->get('cliente_id')->getData();

            $cliente = $this->entityManager->getRepository(Cliente::class)->find([
                'cliente_id' => $clienteId
                    ]);


            if (!$cliente) {
                    $this->addFlash('error', 'El ID del cliente no existe.');
                    return $this->redirectToRoute('app_cpanel_mascota_edit');
                }*/
            $this->entityManager->flush();

            $this->addFlash('success', 'Mascota añadida con éxito.');
            return $this->redirectToRoute('app_cpanel_mascota_show');
        }

        return $this->render('cpanel/edit_mascotas.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/panel-control/delete-mascota/{id}', name: 'app_cpanel_mascota_delete')]
    public function deleteMascota(Mascotas $mascota): Response
    {

        $this->entityManager->remove($mascota);
        $this->entityManager->flush();

        $this->addFlash('success', 'Cliente eliminado con éxito.');
        return $this->redirectToRoute('app_cpanel_mascota_show');
    }

    #[Route('/panel-control/mostrar-mascotas', name: 'app_cpanel_mascota_show')]
    public function showMascota(EntityManagerInterface $entityManager): Response
    {
        $mascotas = $entityManager->getRepository(Mascotas::class)->findAll();

        return $this->render('cpanel/cpanel_mascota.html.twig', [
            'mascotas' => $mascotas,
        ]);
    }

    #[Route('/panel-control/add-vet', name: 'app_cpanel_vet_add')]
    public function addVet(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $vet = new Veterinario();

        $form = $this->createFormBuilder($vet)
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
             ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => ['label' => 'Contraseña',
                    'attr' => [
                        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                        ]],
                'second_options' => ['label' => 'Confirmar Contraseña',
                    'attr' => [
                        'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                        ]
                        ],
                'invalid_message' => 'Las contraseñas deben de ser la misma',
            ])
            ->add('rol', TextType::class, ['label' => 'Rol',
                'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                 ]
             ])
            ->add('especialidad', TextType::class, ['label' => 'Especialidad',
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
             ]
             ])
            ->add('telefono', TextType::class, ['label' => 'Telefono',
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
             ]
             ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $existingVet = $this->entityManager->getRepository(Veterinario::class)->findOneBy(['mail' => $vet->getMail()]);

            if ($existingVet) {
                $this->addFlash('error', 'El correo electrónico ya está registrado.');
                return $this->redirectToRoute('app_cpanel_vet_add');
            }

            
            $vet->setPassword(
                $passwordHasher->hashPassword(
                    $vet,
                    $form->get('plainPassword')->getData()
                )
            );

            
            $this->entityManager->persist($vet);
            $this->entityManager->flush();

        /*    $data = $form->getData();
                $cli = new Cliente();
                $cli->setNombre($data->getNombre());
                $cli->setEdad($data->getEdad());
                $cli->setMail($data->getMail());
                $cli->setRol("ROLE_VET");
                $cli->setDireccion('calle Mayor, 3, Murcia');
                $cli->setTelefono('685780143');
                $cli->setPassword(
                    $passwordHasher->hashPassword(
                        $cli,
                        $form->get('plainPassword')->getData()
                    )
                );
                $this->entityManager->persist($cli);
                $this->entityManager->flush();*/



            $this->addFlash('success', 'Veterinario añadido con éxito.');
            return $this->redirectToRoute('app_cpanel_vet_show');
        }

        return $this->render('cpanel/add_vet.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/panel-control/edit-vet/{id}', name: 'app_cpanel_vet_edit')]
    public function editVet(Request $request, Veterinario $vet): Response
    {

        $form = $this->createFormBuilder($vet)
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
            ->add('rol', TextType::class, ['label' => 'Rol',
                'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                 ]
             ])
            ->add('especialidad', TextType::class, ['label' => 'Especialidad',
            'attr' => [
            'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
             ]
             ])    
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          
            $this->entityManager->flush();

            $this->addFlash('success', 'Veterinario añadido con éxito.');
            return $this->redirectToRoute('app_cpanel_vet_show');
        }

        return $this->render('cpanel/edit_vet.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/panel-control/delete-vet/{id}', name: 'app_cpanel_vet_delete')]
    public function deleteVet(Veterinario $vet): Response
    {

        $this->entityManager->remove($vet);
        $this->entityManager->flush();

        $this->addFlash('success', 'Veterinario eliminado con éxito.');
        return $this->redirectToRoute('app_cpanel_vet_show');
    }

    #[Route('/panel-control/mostrar-vet', name: 'app_cpanel_vet_show')]
    public function showVet(EntityManagerInterface $entityManager): Response
    {
        $veterinarios = $entityManager->getRepository(Veterinario::class)->findAll();

        return $this->render('cpanel/cpanel_vet.html.twig', [
            'veterinarios' => $veterinarios,
        ]);
    }

    #[Route('/panel-control/add-cita', name: 'app_cpanel_cita_add')]
    public function addCitas(Request $request): Response
    {
        $cita = new Citas();

        $form = $this->createFormBuilder($cita)
        ->add('fecha', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Fecha',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
        ])
        ->add('descripcion', TextType::class, [
            'label' => 'Descripción',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2'
            ]
        ])
        ->add('tipo', ChoiceType::class, [
            'choices' => [
                'Consulta' => 'consulta',
                'Consulta Online' => 'consulta_online',
                'Radiografía' => 'radiografia',
                'Vacunación' => 'vacunacion',
                'Peluquería' => 'peluqueria',
            ],
            'label' => 'Tipo de Consulta',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
            ])
             ->add('cliente_id', EntityType::class, [
                'class' => Cliente::class,
                'choice_label' => 'mail',
                'label' => 'Correo del Cliente',
                'placeholder' => 'Selecciona un cliente',
                'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                ]
            ])
            ->add('vet_id', EntityType::class, [
                'class' => Veterinario::class,
                'choice_label' => 'mail',
                'label' => 'Correo del Veterinario',
                'placeholder' => 'Selecciona un Veterinario',
                'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                ]
            ]) 
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

        $cliente = $form->get('cliente_id')->getData();
        $cita->setClienteId($cliente);

        $vet = $form->get('vet_id')->getData();
        $cita->setVetId($vet);

            //Creamos chat si hemos seleccionado consulta en linea
            if($form->getData('tipo') == 'consulta-online'){
                $chat = new Chat();
                $chat->setClienteId($cliente);
                $chat->setVetId($vet);
                $chat->setFecha(new \DateTime());

                $this->entityManager->persist($chat);
                $this->entityManager->flush();
            }

            $this->entityManager->persist($cita);
            $this->entityManager->flush();

            $this->addFlash('success', 'cita añadida con éxito.');

            return $this->redirectToRoute('app_cpanel_cita_show');
        }

        return $this->render('cpanel/add_citas.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/panel-control/edit-cita/{id}', name: 'app_cpanel_cita_edit')]
    public function editCita(Request $request,  Citas $cita): Response
    {
        $form = $this->createFormBuilder($cita)
        ->add('fecha', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Fecha',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
        ])
        ->add('descripcion', TextType::class, [
            'label' => 'Descripción',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2'
            ]
        ])
        ->add('tipo', ChoiceType::class, [
            'choices' => [
                'Consulta' => 'consulta',
                'Consulta Online' => 'consulta_online',
                'Radiografía' => 'radiografia',
                'Vacunación' => 'vacunacion',
                'Peluquería' => 'peluqueria',
            ],
            'label' => 'Tipo de Consulta',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
            ])
            ->getForm();

            
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Cita añadida con éxito.');
            return $this->redirectToRoute('app_cpanel_cita_show');
        }

        return $this->render('cpanel/edit_citas.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/panel-control/delete-cita/{id}', name: 'app_cpanel_cita_delete')]
    public function deleteCita(Citas $cita): Response
    {

        $this->entityManager->remove($cita);
        $this->entityManager->flush();

        $this->addFlash('success', 'Cita eliminada con éxito.');
        return $this->redirectToRoute('app_cpanel_cita_show');
    }

    #[Route('/panel-control/mostrar-citas', name: 'app_cpanel_cita_show')]
    public function showCitas(EntityManagerInterface $entityManager): Response
    {
        $cita = $entityManager->getRepository(Citas::class)->findAll();

        return $this->render('cpanel/cpanel_citas.html.twig', [
            'citas' => $cita,
        ]);
    }


    #[Route('/panel-control/add-producto', name: 'app_cpanel_producto_add')]
    public function addProducto(Request $request): Response
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

            return $this->redirectToRoute('app_cpanel_producto_show');
        }

        return $this->render('cpanel/add_productos.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/panel-control/edit-producto/{id}', name: 'app_cpanel_producto_edit')]
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
            return $this->redirectToRoute('app_cpanel_producto_show');
        }

        return $this->render('cpanel/add_productos.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/panel-control/delete-producto/{id}', name: 'app_cpanel_producto_delete')]
    public function deleteProducto(Producto $prod): Response
    {

        $this->entityManager->remove($prod);
        $this->entityManager->flush();

        $this->addFlash('success', 'Producto eliminado con éxito.');
        return $this->redirectToRoute('app_cpanel_producto_show');
    }

    #[Route('/panel-control/mostrar-producto', name: 'app_cpanel_producto_show')]
    public function showProducto(EntityManagerInterface $entityManager): Response
    {
        $prod = $entityManager->getRepository(Producto::class)->findAll();

        return $this->render('cpanel/cpanel_producto.html.twig', [
            'productos' => $prod,
        ]);
    }


}
