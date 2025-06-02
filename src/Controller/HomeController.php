<?php

namespace App\Controller;

use dump;
use DateTime;
use App\Entity\Mascotas;
use App\Entity\Historial;
use App\Entity\Veterinario;
use App\Repository\AnuncioRepository;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManager;
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


class HomeController extends AbstractController
{   

    private $entityManager;

    // El constructor recibe el EntityManager y lo asigna a la propiedad de la clase
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function home(AnuncioRepository $anuncioRepository): Response
    {   
        $user = $this->getUser();

        $anuncios = $anuncioRepository->findBy([], ['fecha' => 'DESC'], 4);

        return $this->render('home/home.html.twig',[
            'user' => $user,
            'anuncios' => $anuncios
        ]);
    }
        


}
