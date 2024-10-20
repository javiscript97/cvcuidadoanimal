<?php
// src/Controller/ProfileController.php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Mascotas;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        if (!$user instanceof Cliente) {
            throw $this->createAccessDeniedException('No tienes acceso a esta página.');
        }

        // Obtener las mascotas del cliente
        $mascotas = $user->getMascotas();

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
            'mascotas' => $mascotas,
        ]);
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
            ->add('nombre')
            ->add('edad')
            ->add('especie') // Asegúrate de que tengas esta propiedad en la entidad Mascotas
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
        if (!$user instanceof Cliente) {
            throw $this->createAccessDeniedException('No tienes acceso a esta página.');
        }

        $form = $this->createFormBuilder($user)
            ->add('nombre')
            ->add('edad')
            ->add('direccion')
            ->add('mail')
            ->add('telefono')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Perfil actualizado con éxito.');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/editTheProfile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/delete', name: 'app_profile_delete')]
    public function deleteProfile(): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Cliente) {
            throw $this->createAccessDeniedException('No tienes acceso a esta página.');
        }

        // Eliminar la cuenta del usuario
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'Cuenta eliminada con éxito.');
        return $this->redirectToRoute('app_home'); // Cambia 'app_home' a la ruta de inicio de sesión o inicio
    }
    #[Route('/profile/mascota/edit/{id}', name: 'app_profile_edit_mascota')]
    public function editMascota(Request $request, Mascotas $mascota): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Cliente || $mascota->getClienteId() !== $user) {
            throw $this->createAccessDeniedException('No tienes acceso a esta página.');
        }

        $form = $this->createFormBuilder($mascota)
            ->add('nombre')
            ->add('edad')
            ->add('especie')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Mascota modificada con éxito.');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edditingProfile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/mascota/delete/{id}', name: 'app_profile_delete_mascota')]
    public function deleteMascota(Mascotas $mascota): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Cliente || $mascota->getClienteId() !== $user) {
            throw $this->createAccessDeniedException('No tienes acceso a esta página.');
        }

        $this->entityManager->remove($mascota);
        $this->entityManager->flush();

        $this->addFlash('success', 'Mascota eliminada con éxito.');
        return $this->redirectToRoute('app_profile');
    }
}
