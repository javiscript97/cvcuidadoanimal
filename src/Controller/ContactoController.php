<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactoController extends AbstractController
{
    #[Route('/contacto', name: 'app_contacto')]
    public function contacto(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, ['label' => 'Tu correo electrónico',
                'constraints' => [
                    new NotBlank(['message' => 'El correo no puede estar vacío']),
                    new EmailConstraint(['message' => 'Introduce un correo electrónico válido']),
                    ]
                ])
            ->add('mensaje', TextareaType::class, ['label' => 'Mensaje'])
            ->add('enviar', SubmitType::class, [
                'label' => 'Enviar',
                'attr' => ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mensaje = $form->getData();
            $email = (new Email())
                ->from($mensaje['email'])
                ->to('contacto@cvcuidadoanimal.com') 
                ->subject('Comunicación Web')
                ->text($mensaje['mensaje']);

            $mailer->send($email);
            $this->addFlash('success', 'Gracias por tu mensaje. Te contactaremos pronto.');
            return $this->redirectToRoute('app_contacto');
        }


        return $this->render('contacto/contacto.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
