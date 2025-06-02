<?php

namespace App\Controller;

use dump;
use DateTime;
use App\Entity\Cliente;
use App\Entity\Mascotas;
use App\Entity\Historial;
use App\Entity\Veterinario;
use Doctrine\ORM\EntityManager;
use App\Repository\HistorialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Masterminds\HTML5\Exception;
use Symfony\Component\BrowserKit\History;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\DataTransformer\DataTransformerChain;

use function PHPUnit\Framework\throwException;

class HistoryPostController extends AbstractController
{   

    private $entityManager;
    private $security;

    // el constructor -> entityManage  y este lo pasamos a la propiedad de la clase
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[Route('/historial/add', name: 'app_history_post_add')]
public function add(Request $request): Response
{
    $historyPost = new Historial();
    $form = $this->createFormBuilder($historyPost)
        ->add('fecha', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
        ])
        ->add('descripcion', TextareaType::class, [
            'label' => 'Descripción',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2'
            ]
        ])
        ->add('mascota_id', EntityType::class, [
            'class' => Mascotas::class,
            'choice_label' => 'nombre',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
        ])
        ->add('vet_id', EntityType::class, [
            'class' => Veterinario::class,
            'choice_label' => 'mail',
            'attr' => [
                'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Agregar',
            'attr' => [
                'class' => 'block w-full shadow-sm border-transparent bg-indigo-600 hover:bg-indigo-700 text-white rounded-md p-2 mt-4 mb-2'
            ]
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $post = $form->getData();
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $this->addFlash('success', 'Agregado con éxito');
        return $this->redirectToRoute('app_history');
    }

    return $this->render('history_post/add.html.twig', [
        'form' => $form->createView()
    ]);
}


    #[Route('/historial', name: 'app_history')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        //Obtenemos usuario
        $user = $this->security->getUser();

       if($user->getRoles()[0] == "ROLE_VET"){
                $vet = $this->entityManager->getRepository(Veterinario::class)->findOneBy([
                    'mail' => $user->getUserIdentifier()
                ]);
                $historiales = $vet->getHistoriales();
            }
        else{

            $cliente = $this->entityManager->getRepository(Cliente::class)->findOneBy([
                'mail' => $user->getUserIdentifier()
            ]);
            //Saco array de Mascotas
            $mascotas = $cliente->getMascotas();
            //De cada Mascota solo saco sus Ids
            $mascotasIds = [];
            foreach ($mascotas as $mascota) {
                $mascotasIds[] = $mascota->getId();
            }
            //Busco en historial por los ids de mascota
            $historiales = $this->entityManager->getRepository(Historial::class)->findBy([
                'mascota_id' => $mascotasIds
            ]);
        }
        // Pasar los datos a la plantilla
        return $this->render('history_post/index.html.twig', [
            'historiales' => $historiales,
        ]);
    }
 
    #[Route('/historial/edit/{id}', name: 'app_history_post_edit')]
    public function edit(Request $request, Historial $historyPost): Response
    {
        $form = $this->createFormBuilder($historyPost)
            ->add('fecha', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                ]
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-12 mt-1 mb-2'
                ]
            ])
            ->add('mascota_id', EntityType::class, [
                'class' => Mascotas::class,
                'choice_label' => 'nombre',
                'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                ]
            ])
            ->add('vet_id', EntityType::class, [
                'class' => Veterinario::class,
                'choice_label' => 'mail',
                'attr' => [
                    'class' => 'block w-full shadow-sm border-gray-300 rounded-md border p-2 mt-1 mb-2'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Agregar',
                'attr' => [
                    'class' => 'block w-full shadow-sm border-transparent bg-indigo-600 hover:bg-indigo-700 text-white rounded-md p-2 mt-4 mb-2'
                ]
            ])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $this->entityManager->flush();
    
            $this->addFlash('success', 'Agregado con éxito');
            return $this->redirectToRoute('app_history'); 
        }
    
        return $this->render('history_post/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/historial/delete/{id}', name: 'app_history_post_delete')]
    public function delete(Historial $post): Response
    {

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        $this->addFlash('success', 'Dato eliminado con éxito.');
        return $this->redirectToRoute('app_history');
    }




}
