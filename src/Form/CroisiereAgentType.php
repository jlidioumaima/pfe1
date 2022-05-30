<?php

namespace App\Form;

use App\Entity\Croisiere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class CroisiereAgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('description', CKEditorType::class)
            ->add('inclus')
            ->add('Non_Inclus')
            
            
            ->add('titre')
           
            ->add('pays')
            ->add('categorie')

            ->add('images', FileType::class,[
                'label' => "Images",
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Croisiere::class,
        ]);
    }
}
