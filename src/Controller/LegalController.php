<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    #[Route('/aviso-legal', name: 'app_aviso_legal')]
    public function avisoLegal(): Response
    {
        return $this->render('legales/aviso_legal.html.twig');
    }

    #[Route('/politica-privacidad', name: 'app_politica_privacidad')]
    public function politicaPrivacidad(): Response
    {
        return $this->render('legales/privacidad.html.twig');
    }

    #[Route('/condiciones-uso', name: 'app_condiciones_uso')]
    public function condicionesUso(): Response
    {
        return $this->render('legales/condiciones_uso.html.twig');
    }
}
