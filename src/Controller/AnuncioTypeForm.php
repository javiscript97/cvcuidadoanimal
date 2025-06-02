<?php
namespace App\Controller;

use App\Entity\Anuncio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AnuncioTypeForm extends AbstractType
{
 public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título del anuncio',
            ])
            ->add('contenido', TextareaType::class, [
                'label' => 'Contenido',
                'attr' => ['rows' => 10]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Anuncio::class,
        ]);
    }
}