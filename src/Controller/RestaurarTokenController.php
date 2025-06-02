<?php
namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class RestaurarTokenController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

     #[Route('/restaurar-password', name: 'app_request_link')]
    public function requestReset(Request $request, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('_username');
            $user = $this->entityManager->getRepository(Usuario::class)->findOneBy(['mail' => $email]);
          //  dd($email, $user);
            if ($user) {
                
                $token = Uuid::v4();
                $user->setResetToken($token);
                $user->setRestauraTokenTiempo(new \DateTime('+1 hour'));
                $this->entityManager->flush();
               //  dd('Token generado:', $token, $user->getResetToken(), $user->getRestauraTokenTiempo());
                $restauraLink = $this->generateUrl('app_restaurar_password', ['token' => $token], 0);
                $emailMsg = (new Email())
                    ->from('no-reply@cvcuidadoanimal.com')
                    ->to($email)
                    ->subject('Reestablecer tu contraseña')
                    ->html("<p>Haz clic en el siguiente enlace para cambiar tu contraseña:</p><a href='$restauraLink'>$restauraLink</a>");

                $mailer->send($emailMsg);

                $this->addFlash('success', 'Hemos enviado un enlace a tu email.');
            } else {
                $this->addFlash('error', 'No hemos encontrado su correo.');
            }
        }

        return $this->render('login/reset-token.html.twig');
    }

    #[Route('/restaurar-password/{token}', name: 'app_restaurar_password')]
    public function resetPassword(string $token, Request $request, UserPasswordHasherInterface $passwordHasher): Response 
    {
        $user = $this->entityManager->getRepository(Usuario::class)->findOneBy(['restauraToken' => $token]);

        if (!$user || $user->getRestauraTokenTiempo() < new \DateTime()) {
            $this->addFlash('error', 'El enlace es inválido o ha expirado.');
            return $this->redirectToRoute('app_request_link');
        }

        $form = $this->createForm(ResetPasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();

            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $user->setResetToken(null);

            $this->entityManager->flush();

            $this->addFlash('success', 'Tu contraseña ha sido actualizada correctamente.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('login/reset_pass_form.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }

}