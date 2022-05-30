<?php

namespace App\Form;

use App\Entity\Omra;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class OmraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            
           
            ->add('agence')
         

            
            ->add('inclus',TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('Non_Inclus',TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
        
            ->add('description', CKEditorType::class)

            ->add('programme')
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
            'data_class' => Omra::class,
        ]);
    }
}
