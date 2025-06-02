<?php 


namespace App\Controller;

use App\Entity\Cliente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cliente::class,
        ]);
    }
}
