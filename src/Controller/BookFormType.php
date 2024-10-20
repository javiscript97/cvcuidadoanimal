<?php
namespace App\Controller;

use App\Entity\Citas;
use App\Entity\Cliente;
use App\Entity\Veterinario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class BookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ]);
    }

public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Citas::class,
        ]);
    }

}