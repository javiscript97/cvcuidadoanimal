<?php
namespace App\Controller;

use App\Entity\Anuncio;
use App\Entity\Usuario;
use App\Entity\Veterinario;
Use App\Controller\AnuncioTypeForm;
use App\Repository\AnuncioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class TablonAnunciosController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/anuncio/nuevo', name: 'app_anuncio_nuevo')]
    public function crearAnuncio(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_VET');

        $anuncio = new Anuncio();
        $form = $this->createForm(AnuncioTypeForm::class, $anuncio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $anuncio->setFecha(new \DateTime());
            $anuncio->setVetId($this->getUser());

            $this->entityManager->persist($anuncio);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('tablon_anuncios/nuevo_anuncio.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tablon', name: 'app_tablon_anuncios')]
    public function listadoAnuncio(AnuncioRepository $anuncioRepository): Response
    {
        $anuncios = $anuncioRepository->findBy([], ['fecha' => 'DESC']);

        return $this->render('tablon_anuncios/tablon_anuncios.html.twig', [
            'anuncios' => $anuncios
        ]);
    }

    #[Route('/tablon/{id}', name: 'app_ver_anuncio')]
    public function mostrarAnuncio(Anuncio $anuncio): Response
    {
        return $this->render('tablon_anuncios/anuncio.html.twig', [
            'anuncio' => $anuncio
        ]);
    }

    #[Route('/anuncio/{id}/editar', name: 'app_anuncio_edit')]
    public function editarAnuncio(Request $request, Anuncio $anuncio): Response
    {

        $form = $this->createForm(AnuncioTypeForm::class, $anuncio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Anuncio modificado correctamente.');
            return $this->redirectToRoute('app_tablon_anuncios');
        }

        return $this->render('tablon_anuncios/nuevo_anuncio.html.twig', [
            'form' => $form,
            'editar' => true
        ]);
    }

    #[Route('/anuncio/{id}/eliminar', name: 'app_anuncio_delete')]
    public function eliminarAnuncio(Anuncio $anuncio): Response
    {
        $this->entityManager->remove($anuncio);
        $this->entityManager->flush();

        $this->addFlash('success', 'Anuncio eliminado correctamente.');
        return $this->redirectToRoute('app_tablon_anuncios');
    }
}
