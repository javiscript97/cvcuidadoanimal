<?php 


namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            'data_class' => Usuario::class,
        ]);
    }
}
