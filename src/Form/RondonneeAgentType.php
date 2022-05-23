<?php

namespace App\Form;

use App\Entity\Rondonnee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RondonneeAgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('inclus')
            ->add('Non_Inclus')
           
            ->add('titre')
            ->add('categorie')

            ->add('pays')
            ->add('images', FileType::class,[
                'label' => true,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'label'=>"Images"
            ])
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rondonnee::class,
        ]);
    }
}
