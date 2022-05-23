<?php

namespace App\Form;

use App\Entity\VoyageOrganiser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class VoyageAgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description',TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('categorie')

            ->add('inclus',TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('Non_Inclus',TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('titre')
           
            ->add('pays')
            ->add('images', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'label'=>"Images"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VoyageOrganiser::class,
        ]);
    }
}
